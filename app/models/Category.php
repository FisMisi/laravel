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
    
     public static function getCategories($type,$multiple)
    {
        $query = self::join('category_types', 'category_types.id', '=', 'categories.type_id')
                 ->select('category_types.id as typeId',
                          'category_types.title as typeTitle',
                          'category_types.name as typeName',
                          'categories.id as categId',
                          'categories.title as categTitle',
                          'categories.name as categName',
                          'category_types.multi as multi'
                         )
                 ->where('categories.type_id', '=', $type)
                 ->where('category_types.multi', '=', $multiple)
                 ->where('categories.active', '=', 1)
                 ->where('category_types.active', '=', 1);
       
        return $query->get();     
    }
    
}