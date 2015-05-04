<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

 /**
 *Modelleknek lehetőségük van önmagukat besorolni különböző kategóriákba
  *  
 */ 

class ModelCategory extends Eloquent 
{
    public    $timestamps = false;	
    protected      $table = 'model_categories';

    protected $primaryKey = 'id';
    
     public static $rules = [
        'name'   => array('required', 'min:2', 'alpha_dash', 'unique'=>'unique:model_categories,name'),
        'title'  => 'required|min:2',
        'pos'    => 'integer',
        'type_id'=> 'required|integer' 
    ];
     
     /**
    * Admin oldalon a fő kategóriákhoz tartozó allkategóriák megjelenítéséhez szükséges szűrési feltételek összegyűjtése.
    * 
    * @param  int  $active, $validated, $country, $payout, $limit, $page
    * @return array $ret
    */ 
     
    public static function getCategoriesToList($active, $categoryType,$limit, $page) 
    {
        if ($page != 0) {
                $page--;
        }
        
        $query = self::where('model_categories.type_id', '=', $categoryType);
        $query2 = self::where('model_categories.type_id', '=', $categoryType);
        
        
        if ($active != 2) {
                $query ->where('model_categories.active', '=', $active);
                $query2->where('model_categories.active', '=', $active);
        }

        $query->take($limit);
        if ($page > 0) {
                $query->skip($limit*$page);
        }
        
        //megkjelenítendő mezők összegyűjtése
        $getArray = array('model_categories.id',
                         'model_categories.name',
                        'model_categories.title',
                        'model_categories.active'
                        );
        
        $ret = [];
        $ret['categories'] = $query->get($getArray)->toArray();
        $ret['count']  = $query2->count();
        
        return $ret;
    }
    
    /**
    * Public oldalon a model regisztráció step2 fő testalkat kategóriákhoz tartozó allkategóriák megjelenítéséhez szükséges szűrési feltételek összegyűjtése.
    * 
    * @param  int  $type, $modelId
    * @return array $ret
    */ 
    
    public static function getCategories($type, $modelId)
    {   #DB::enableQueryLog();
        $query = self::leftJoin('model_model_category', function($join) use($modelId)
            {
            $join->on('model_model_category.category_id', '=', 'model_categories.id')
                ->where('model_model_category.model_id', '=', $modelId);
            })
                ->where('model_categories.type_id', '=', $type)
                ->where('model_categories.active', '=', 1);
        $ret = $query->orderBy('model_categories.id', 'desc')->get(array('model_categories.id','model_categories.title','model_model_category.model_id'))->toArray();     
        /*$queries = DB::getQueryLog();
        $last_query = end($queries);
        var_dump($last_query);*/
        return $ret;
    }
        
    public static function getOrderedDatas() {
        $getArray = array();
        $getArray[] = 'model_category_types.title as type_title';
        $getArray[] = 'model_category_types.id as type_id';
        $getArray[] = 'model_category_types.pos as typepos';
        $getArray[] = 'model_categories.title as title';
        $getArray[] = 'model_categories.id as id';
        $getArray[] = 'model_categories.pos as catpos';
        
        $query = self::join('model_category_types', 'model_categories.type_id', '=', 'model_category_tyoes.id');
        $query->where('model_categories.active', '=', 1);
        $query->where('model_category_types.active', '=', 1);
        $query->orderBy('model_category_types.pos', 'asc');
        $query->orderBy('model_categories.pos', 'asc');
        return $query->get($getArray)->toArray();
    }
}
