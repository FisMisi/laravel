<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Model extends Eloquent 
{
    protected $table = 'models';

    protected $primaryKey = 'id';
    
    // step1 form validálási szabályok létrehozáskor
    public static $step1_rules = array(
            'artist_name'          => array('required', 'min:2', 'alpha_dash', 'unique'=>'unique:models,artist_name'),
            'fullname'             => 'required|min:2',
            'payout_system_id'     => 'required',
            'country_id'           => 'required|not_in:default',
            'city'                 => 'required',
            'address'              => 'required',
            'accept_tor'           => 'required',
            'img_path'             => 'required|image|mimes:jpeg,jpg,bmp,gif'
    );
    // step1 form validálási szabályok adatok frissítésekor
    public static $step1_update_rules = array(
            'artist_name'          => array('required', 'min:2', 'alpha_dash', 'unique'=>'unique:models,artist_name'),
            'fullname'             => 'required|min:2',
            'payout_system_id'     => 'required',
            'country_id'           => 'required|not_in:default',
            'city'                 => 'required',
            'address'              => 'required',
            'img_path'             => 'image|mimes:jpeg,jpg,bmp,png,gif'
    ); 
    
    //step2 roules
    public static $step2_rules = array(
           'introducte'             => 'required',
           'gs_languages'           => 'required',
           'gs_video_categories'    => 'required',
    );
    
    //admin oldali validálási szabályok a modelre vonatkozóan

    public static $admin_rules = array(
            'artist_name'          => array('required', 'min:2', 'alpha_dash', 'unique'=>'unique:models,artist_name'),
            'fullname'             => 'required|min:2',
            'payout_system_id'     => 'required',
            'country_id'           => 'required|not_in:default',
            'city'                 => 'required',
            'address'              => 'required',
            'img_path'             => 'image|mimes:jpeg,jpg,bmp,png,gif',
            'introducte'           => 'required'
    ); 
    
    //protected $fillable = ['first_name','password','last_name', 'username'];
    
    
     /**
    * Public oldalon a modelek megjelenítéséhez testalkat és gift show alapján.
    * 
    * @param  
    * @return array $ret
    */ 
     
     public static function getModelListByMsGs($ms, $gs, $needPager, $modelLimitPerPage, $page) 
    {
        if ($page != 0) {
                $page--;
        }
        
        $ret = [];
       
        //array_agg() => postgre, grouppolt mezőket szedi össze, jelen esetben a title-ket
        $getString = " models.id,"
                   . " array_agg(distinct(model_categories.title)) as mct,"
                   . " array_agg(distinct(gs_video_categories.title)) as vct,"
                   . " img_path,"
                   . " artist_name,"
                   . " countries.country_name";
         
        //kapcsolódás az országokhoz
            $query = self::join('countries','countries.country_id','=','models.country_id');
        //kapcsolódás a model kategóriákhoz
            $query->join('model_model_category as mc','mc.model_id','=','models.id');
            $query->join('model_categories','model_categories.id','=','mc.category_id');
        
        //kapcsolódás a model videó kategóriákhoz
            $query->join('model_gs_vc as vcw','vcw.model_id','=','models.id');
            $query->join('gs_video_categories','gs_video_categories.id','=','vcw.gs_vc_id');
            
        if(!empty($ms))
        {
             $i = 0;    
            foreach($ms as $id) {
                $i++;
                $query->join('model_model_category as mc'.$i, function($join) use($id,$i)
                {
                    $join->on('mc'.$i.'.model_id', '=', 'models.id');
                    $join->where('mc'.$i.'.category_id', '=', $id);  
                });
            }
        }
      
        if(!empty($gs))
        {   
            $i = 0;    
            foreach($gs as $id) {
                $i++;
                $query->join('model_gs_vc as vc'.$i, function($join) use($id, $i)
                {
                    $join->on('vc'.$i.'.model_id', '=', 'models.id');
                    $join->where('vc'.$i.'.gs_vc_id', '=', $id);  
                });
            }
        }
        
        $query->where('models.active', '=', 1);
        $query->where('models.validated', '=', 1);
        
        $query->groupBy('models.id');
        $query->groupBy('img_path');
        $query->groupBy('artist_name');
        $query->groupBy('countries.country_name');
        
        $query->take($modelLimitPerPage);
        if ($page > 0) {
                $query->skip($modelLimitPerPage*$page);
        }
     
        $ret['models'] = $query->orderBy('models.id', 'desc')->selectRaw($getString)->get()->toArray();
       
        //mct string tömb, ezt kell átalakítani rendes tömbé
        foreach($ret['models'] as $key => $item) 
        {
          $ret['models'][$key]['mct'] = explode(',',str_replace('{','',str_replace('}','',str_replace('"', '', $ret['models'][$key]['mct']))));  
          $ret['models'][$key]['vct'] = explode(',',str_replace('{','',str_replace('}','',str_replace('"', '', $ret['models'][$key]['vct']))));       
        }
        
        return $ret;
    }
    
//    public static function getModelListByMs($ms, $needPager, $modelLimitPerPage, $page) 
//    {
//         if ($page != 0) {
//                $page--;
//        }
//    
//        $ms = array(2);
//        
//        $i = 0;
//        $query = self::join('countries','countries.country_id','=','models.country_id');
//        foreach($ms as $id) {
//            $i++;
//            $query->join('model_model_category as mc'.$i, function($join) use($id,$i)
//            {
//                $join->on('mc'.$i.'.model_id', '=', 'models.id');
//                $join->where('mc'.$i.'.category_id', '=', $id);  
//            });
//        }
//        
//        $query->join('model_model_category as mc','mc.model_id','=','models.id');
//        $query->join('model_categories','model_categories.id','=','mc.category_id');
//        $query->where('models.active', '=', 1);
//        $query->where('models.validated', '=', 1);
//        
//        $query->groupBy('models.id');
//        $query->groupBy('img_path');
//        $query->groupBy('artist_name');
//        $query->groupBy('countries.country_name');
//        
//        $query->take($modelLimitPerPage);
//        if ($page > 0) {
//                $query->skip($modelLimitPerPage*$page);
//        }
//        
//        
//        $ret = [];
//        //array_agg() => postgre, grouppolt mezőket szedi össze, jelen esetben a title-ket
//        $getString = "models.id, array_agg(model_categories.title) as mct, img_path, artist_name, countries.country_name";
//        $ret['models'] = $query->orderBy('models.id', 'desc')->selectRaw($getString)->get()->toArray();
//    
//        //mct string tömb, ezt kell átalakítani rendes tömbé
//        foreach($ret['models'] as $key => $item) {
//          $ret['models'][$key]['mct'] = explode(',',str_replace('{','',str_replace('}','',str_replace('"', '', $ret['models'][$key]['mct']))));  
//        }
//        
//        return $ret;
//    }
    
    /**
    * Public oldalon a modelek megjelenítéséhez show kategória alapján.
    * 
    * @param  
    * @return array $ret
    */ 
     
//    public static function getModelListByGs($gs, $needPager, $modelLimitPerPage, $page) 
//    {
//         if ($page != 0) {
//                $page--;
//        }
//    
//        $gs = array(1);
//        
//        $query = self::join('countries','countries.country_id','=','models.country_id');
//       
//            $query->join('model_gs_vc as vc', function($join) use($gs)
//            {
//                $join->on('vc.model_id', '=', 'models.id');
//                $join->where('vc.gs_vc_id', '=', $gs);  
//            });
//        
//        $query->join('model_gs_vc as vcw','vcw.model_id','=','models.id');
//        $query->join('gs_video_categories','gs_video_categories.id','=','vcw.gs_vc_id');
//        $query->where('models.active', '=', 1);
//        $query->where('models.validated', '=', 1);
//        
//        $query->groupBy('models.id');
//        $query->groupBy('img_path');
//        $query->groupBy('artist_name');
//        $query->groupBy('countries.country_name');
//        
//        $query->take($modelLimitPerPage);
//        if ($page > 0) {
//                $query->skip($modelLimitPerPage*$page);
//        }
//        
//        
//        $ret = [];
//        //array_agg() => postgre, grouppolt mezőket szedi össze, jelen esetben a title-ket
//                $getString = "models.id, array_agg(gs_video_categories.title) as vct, img_path, artist_name, countries.country_name";
//                $ret['models'] = $query->orderBy('models.id', 'desc')->selectRaw($getString)->get()->toArray();
//
//                //mct string tömb, ezt kell átalakítani rendes tömbé
//                foreach($ret['models'] as $key => $item) {
//                  $ret['models'][$key]['vct'] = explode(',',str_replace('{','',str_replace('}','',str_replace('"', '', $ret['models'][$key]['vct']))));  
//                }
//        
//        return $ret;
//    }
    
    
    /**
    * Admin oldalon a modelek megjelenítéséhez szükséges szűrési feltételek összegyűjtése.
    * 
    * @param  int  $active, $validated, $country, $payout, $limit, $page
    * @return array $ret
    */ 
     
    public static function getModelToAdminList($active = 2, $validated = 2, $country =0, $payout=0, $autoLevel=2, $level=0, $accept_tor=2, $limit=20, $page=1) 
    {
        if ($page != 0) {
                $page--;
        }

        $query = self::join('countries', 'countries.country_id', '=', 'models.country_id')
                 ->join('gs_modell_levels', 'gs_modell_levels.id', '=', 'models.model_level_id')
                 ->join('payput_system', 'payput_system.pos_id', '=', 'models.payout_system_id');
        $query2 = self::join('countries', 'countries.country_id', '=', 'models.country_id')                   //count miatt kell a query2
                 ->join('payput_system', 'payput_system.pos_id', '=', 'models.payout_system_id');  
        
        if ($active != 2) {
                $query->where('models.active', '=', $active);
                $query2->where('models.active', '=', $active);
        }
        
        if ($validated != 2) {
                $query->where('models.validated', '=', $validated);
                $query2->where('models.validated', '=', $validated);
        }
        
        if ($country != 0) {
                $query->where('models.country_id', '=', $country);
                $query2->where('models.country_id', '=', $country);
        }

        if ($level != 0) {
                $query->where('models.model_level_id', '=', $level);
                $query2->where('models.model_level_id', '=', $level);
        }
        
        if ($autoLevel != 2) {
                $query->where('models.is_manual', '=', $autoLevel);
                $query2->where('models.is_manual', '=', $autoLevel);
        }
        
        if ($payout != 0) {
                $query->where('models.payout_system_id', '=', $payout);
                $query2->where('models.payout_system_id', '=', $payout);
        }
        
        if ($accept_tor != 2) {
                $query->where('models.accept_tor', '=', $accept_tor);
                $query2->where('models.accept_tor', '=',$accept_tor);
        }

        $query->take($limit);
        if ($page > 0) {
                $query->skip($limit*$page);
        }
        
        //megkjelenítendő mezők összegyűjtése
        $getArray = array(
                          'models.id',
                          'accept_tor',
                          'artist_name',
                          'fullname',
                          'models.active',
                          'validated',
                          'countries.country_id',
                          'countries.country_name',
                          'gs_modell_levels.title',
                          'model_level_id',
                          'is_manual',
                          'payput_system.pos_title');
        
        $ret = [];
        $ret['models'] = $query->orderBy('models.id', 'desc')->get($getArray)->toArray();
        $ret['count']  = $query2->count();
        
        return $ret;
    }
    
    
    public function unsetManulLevel() {
        $this->is_manual = 0;
        $this->regenerateModelLevel();
    }
    
    public function setManualLevel($isManual = false, $levelId = null) {
        if ($isManual) {
            $this->is_manual = 1;
            $this->model_level_id = $levelId;
            $this->save();
        } else {
            $this->unsetManulLevel();
        }
    } 
    
    public function getRating() {
        $svs = StoragedVideo::where('model_id', '=', $this->id)->get();
        $fullRN = 0;
        $fullSR = 0;
        foreach($svs as $sv) {
            $fullRN+= $sv->rating_number;
            $fullSR+= $sv->sum_rating;
        }
        if ($fullRN == 0) {
            return 0;
        }
        return $fullSR/$fullRN;
    }
    
    public static function getView() {
        $svs = StoragedVideo::where('model_id', '=', $this->id)->get();
        $view = 0;
        foreach($svs as $sv) {
            $ssv = StoragedSeeVideo::where('storaged_video_id', '=', $sv->id)->first();
            if (!is_null($ssv)) {
                $view+= $ssv->see_count;
            }
        }
        return $view;
    }
    
    public static function getViewWeek() {
        $svs = StoragedVideo::where('model_id', '=', $this->id)->get();
        $view = 0;
        foreach($svs as $sv) {
            $view+= StoragedSeeVideosDay::get7DaySeeToStoragedVideoId($sv->id);
        }
        return $view;
    }
    
    //bekategorizálja a modelt
    public function regenerateModelLevel() {
        $view = $this->getView();
        $viewWeek = $this->getViewWeek();
        $rating = $this->getRating();
        $query = GsModellLevel::where('min_view', '<=', $view);
        $query->where('min_view_p_week', '<=', $viewWeek);
        $query->where('min_rating', '<=', $rating);
        $level = $query->orderBy('pos', 'DESC')->first();
        if (is_null($level)) {
            return false;
        }
        $this->model_level_id = $level->id;
        $this->save();
        return true;
    }
    	
    public static function regenerateModelLevels() {
        $models = self::where('is_manual', '=', 0)->get();
        foreach($models as $model) {
            $model->regenerateModelLevel();
        }
    }
    
    
}
