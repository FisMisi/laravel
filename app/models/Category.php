<?php

class Category extends Eloquent
{
    protected $fillable = ['name'];
    protected $table = 'categories';
    public static $rules = [
        'name' => 'required|min:3'
    ];
    
    public function items()
    {
        return $this->hasMany('Menuitem');
    }
}