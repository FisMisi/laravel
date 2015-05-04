<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Tárolt videók (model videok és belső videók) típusainak (mit vállal) tárolására szolgáló tábla
 */

class GsVideoCategory extends Eloquent
{       
        public    $timestamps = false;
	protected $table = 'gs_video_categories';
	protected $primaryKey = 'id';
	
	 
        public static $rules = array(
            'name'  => 'required',
            'title' => 'required',
            'active'=> 'integer'
            );
        
    /**
    * public oldalon megjelenítendő kategóriák, ha van id, akkor update esetén hozzá is rendeljük a modelt    
    * 
    * @param  int  $modelId
    * @return array() - categories
    */    
    public static function getShowCategories($modelId)
    {   
        $query = self::leftJoin('model_gs_vc', function($join) use($modelId)
            {
            $join->on('model_gs_vc.gs_vc_id', '=', 'gs_video_categories.id')
                ->where('model_gs_vc.model_id', '=', $modelId);
            });
                
        $ret = $query->orderBy('gs_video_categories.id', 'desc')->get(array(
                                'gs_video_categories.id',
                                'gs_video_categories.title',
                                'model_gs_vc.model_id'
                                ))->toArray();     

        return $ret;
    } 
    
    
    /**
    * admin oldalon a modelhez tartozó bevállalt videó kategóriák megjelenítéséhez
    * 
    * @param  int  $modelId
    * @return array() - categories
    */    
    public static function getVideoCategoriesAdmin($modelId)
    {   
        $query = self::join('model_gs_vc','gs_video_categories.id','=','model_gs_vc.gs_vc_id')
                     ->where('model_gs_vc.model_id','=',$modelId);
           
                
        $ret = $query->orderBy('gs_video_categories.id', 'desc')->get(array(
                                            'gs_video_categories.title'
                                            ))->toArray();      
        return $ret;
    }
    
    /**
    * public oldalon a modelhez tartozó bevállalt videó kategóriák megjelenítéséhez
    * 
    * @param  int  $modelId
    * @return array() - categories
    */    
    public static function getVideoCategoriesPublic($modelId)
    {   
        $query = self::join('model_gs_vc','gs_video_categories.id','=','model_gs_vc.gs_vc_id')
                     ->where('model_gs_vc.model_id','=',$modelId);
           
                
        $ret = $query->orderBy('gs_video_categories.id', 'desc')->get(array(
                                            'gs_video_categories.id as categId',
                                            'gs_video_categories.title as categTitle',
                                            'model_gs_vc.ex_vc_price',
                                            'model_gs_vc.model_id'
                                            ))->toArray();      
        return $ret;
        
    }
    
    /**
    * Admin oldalon a modell videó kategóriához tartozó költségek összegyűjtése
    * 
    * @return array $ret
    */
    public function getGsMlsGsVc() {
        
        $datas = GsMlsGsVc::where('gs_video_category_id', '=', $this->id)->get()->toArray();
        
        $ret = array();
        foreach($datas as $data) {
            $ret[$data['is_exclusive']][$data['gs_model_level_id']]['min'] = $data['min'];
            $ret[$data['is_exclusive']][$data['gs_model_level_id']]['max'] = $data['max'];
            $ret[$data['is_exclusive']][$data['gs_model_level_id']]['referenced_price'] = $data['referenced_price'];
        }
        return $ret;
    }
    
    /**
    * Public oldalon a modell videó kategóriához tartozó költségek összegyűjtése
    * @param int $modelLevelId modell szintjének azonosítója
    * @return array $ret
    */
//    public function getVideoCategoryPrice($modelLevelId) {
//        
//        $query = GsMlsGsVc::where('gs_video_category_id', '=', $this->id)
//                            ->where('gs_model_level_id', '=', $modelLevelId)
//                            ->get()
//                            ->toArray();
//        
//        $ret = array();
//        
//        foreach($query as $data) {
//            $ret[$data['is_exclusive']][$data['gs_model_level_id']]['min'] = $data['min'];
//            $ret[$data['is_exclusive']][$data['gs_model_level_id']]['max'] = $data['max'];
//            $ret[$data['is_exclusive']][$data['gs_model_level_id']]['referenced_price'] = $data['referenced_price'];
//        }
//        return $ret;
//    }
    
    
    /**
    * Admin oldalon a modell videó kategória és hozzá szintek költségeivel megjelenítése
    * @param int $categId 
    * @return object $query
    */ 
    public static function getCategLevels($categId) 
    {
        $retArray = [
            'gs_video_categories.id as categId',
            'gs_video_categories.title as categTitle',
            'gs_video_categories.active',
            'gs_mls_gs_vc.min',
            'gs_mls_gs_vc.min',
            'gs_mls_gs_vc.referenced_price',
            'gs_mls_gs_vc.is_exclusive',
            'gs_modell_levels.id as levelId',
            'gs_modell_levels.title as levelTitle',
        ];
     
        $query = self::join('gs_mls_gs_vc', 'gs_video_categories.id', '=', 'gs_mls_gs_vc.gs_video_category_id')
                     ->join('gs_modell_levels', 'gs_mls_gs_vc.gs_model_level_id', '=', 'gs_modell_levels.id');
        
        $query->where('gs_video_categories.id','=',$categId);
        
        $query->get($retArray);
         
        return $query;
    }
}