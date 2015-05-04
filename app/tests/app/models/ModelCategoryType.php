<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

 /**
 *Modellek kategórizált csoportjaiba tartozó típusok 
  * ezen okmányok útvonalának tárolására létrehozott tábla 
 */ 

class ModelCategoryType extends Eloquent 
{
    public $timestamps = false;	
    protected   $table = 'model_category_types';

    protected $primaryKey = 'id';
    
    public static $rules = [
        'name'   => array('required', 'min:2', 'alpha_dash', 'unique'=>'unique:model_category_types,name'),
        'title'  => 'required|min:2',
        'pos'    => 'integer',
        'active' => 'integer',
        'multi'  => 'required|integer'
    ];
    
   
     /**
    * Admin oldalon a fő kategóriák megjelenítéséhez szükséges szűrési feltételek összegyűjtése.
    * 
    * @param  int  $active, $validated, $country, $payout, $limit, $page
    * @return array $ret
    */ 
    
    public static function getCategoryTypeList($active, $limit, $page) 
    {
        if ($page != 0) {
                $page--;
        }
        $ret = []; 
  
        if ($active != 2){
            $query = self::where('active', '=', $active)->orderBy('id', 'desc')->get();
            $query2 = self::where('active', '=', $active);
        }else{
            $query = self::all();
            $query2 = self::all();
        }
            
        $query->take($limit);
        if ($page > 0) {
                $query->skip($limit*$page);
        }
       
        $ret['categories'] = $query->toArray();
        $ret['count']      = $query2->count();
        return $ret;
    }
    
    /**
    * Public oldalon a fő kategóriák megjelenítése 
    * @return array $ret
    */ 
    
    public static function getCategoryTypes() 
    {
        $query = self::where('active', '=', 1);
        
        return $query->orderBy('id', 'desc')->get()->toArray();
    }
    
    /**
    * Admin oldalon a modellhez tartozó kategória típusok és az alá tartozó kategóriák listázása
    * 
    * @param  int  $modelId
    * @return array $ret
    */ 
    
    public static function getTypesCategoriesAdmin($modelId)
    {   #DB::enableQueryLog();
        
        $query = self::join('model_model_category','model_model_category.type_id', '=', 'model_category_types.id')
                      ->join('model_categories','model_model_category.category_id', '=', 'model_categories.id')
                      ->where('model_model_category.model_id', '=', $modelId)
                      ->groupBy('model_category_types.id')
                      ->groupBy('model_category_types.title');
        
        $rowGetString = "model_category_types.id, array_agg(model_categories.title) as category, model_category_types.title as type";
        
        $ret = $query->orderBy('model_category_types.id', 'desc')
                ->selectRaw($rowGetString)
                ->get()->toArray();     
        
        
        foreach($ret as $key => $item) {
          $ret[$key]['category'] = explode(',',str_replace('{','',str_replace('}','',str_replace('"', '', $ret[$key]['category']))));  
        }
        
        return $ret;
    }
    
}
