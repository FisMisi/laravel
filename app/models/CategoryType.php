<?php

class CategoryType extends Eloquent
{
    //protected $fillable = ['name'];
    protected $table      = 'category_types';
    public    $timestamps = false;
    
    public static $rules = [
        'name'        => 'required|min:3',
        'title'       => 'required|min:3',
        
    ];
 
    public static function getTypes()
    {   
        $query = self::where('active', '=', 1);
 
        return $query->get()->toArray();
    }
    
    
}