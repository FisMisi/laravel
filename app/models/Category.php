<?php

class Category extends Eloquent
{
    public    $timestamps = false;
    protected $table    = 'categories';
    
    public static $rules = [
        'name' => 'required|min:3',
        'type_id' => 'required|integer'
    ];
    
    public static function getQuery($categoryType)
    {
        $query = self::where('type_id','=',$categoryType);
        
        return $query->paginate(10);
        
    }
    
    public static function getCategories($type, $userId)
    {   #DB::enableQueryLog();
        $query = self::leftJoin('user_category', function($join) use($userId)
            {
                $join->on('user_category.category_id', '=', 'categories.id')  
                ->where('user_category.user_id', '=', $userId);
            })
                ->where('categories.type_id', '=', $type)
                ->where('categories.active', '=', 1);
            
        $ret = $query->orderBy('categories.id')->get(array('categories.id as categId',
                                 'categories.title as categTitle',
                                 'user_category.user_id as userId')
                     )->toArray();     
        /*$queries = DB::getQueryLog();
        $last_query = end($queries);
        var_dump($last_query);*/
        return $ret;
    }
    
    public static function getCategoriesByUserId($userId)
    {
        $query = self::join('user_category', 'user_category.category_id', '=', 'categories.id')
                 ->where('user_category.user_id', '=', $userId)
                 ->get(array(
                     'categories.title as name'
                 ));
        
        return $query;
    }
    
}