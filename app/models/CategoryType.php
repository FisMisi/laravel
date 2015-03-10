<?php

class CategoryType extends Eloquent
{
    //protected $fillable = ['name'];
    protected $table      = 'category_types';
    public    $timestamps = false;
    
    public static $rules = [
        'name'        => 'required|min:3',
        'title'       => 'required|min:3',
        'category_id' => 'required|integer'
    ];
    
}