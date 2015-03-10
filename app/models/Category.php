<?php

class Category extends Eloquent
{
    protected $fillable = ['name'];
    protected $table    = 'categories';
    
    public static $rules = [
        'name' => 'required|min:3'
    ];
    
    public static function getQuery($category, $categoryType)
    {
        $query = self::join('category_types','categories.id','=','category_types.category_id');
        
        if($category != 0){
           $query = $query->where('categories.id','=',$category);
        }
        
        if($categoryType != 0){
           $query = $query->where('categories.category_id','=',$categoryType);
        }
        
        return $query->paginate(10,array(
              'categories.name as categoryName',
              'category_types.name as categoryTypeName',
              'category_types.id as categoryTypeId',
              'categories.id as categoryId'
            ));
        
    }
    
}