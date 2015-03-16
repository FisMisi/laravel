<?php

class UserCategory extends Eloquent
{
    //protected $fillable = ['name'];
    protected $table      = 'user_category';
    
    public static $rules = [
        'user_id'     => 'required|integer',
        'type_id'     => 'required|integer',
        'category_id' => 'required|integer',
    ];
 
}