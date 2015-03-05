<?php

class Menuitem extends Eloquent 
{
    use SoftDeletingTrait;
    
    protected $fillable = ['name', 'price', 'image', 'availability', 'category_id'];
    protected $table = 'menuitems';
    public    $timestamps = false;
    
    public static $types = [
        "étel" => "étel",
        "ital" => "ital"
    ];
    
    public static $rules = [
        "name"        => array('required','max:30'),
        "price"       => array('required','numeric'),
        "category_id" => array('required','integer'),
        "image"       => array('required','image','mimes:jpeg,jpg,bmp,png,gif'),
        "availability"=> 'integer'
    ];
    
    public static function getReady()
    {
        return self::where('availability', '=', 1)->orderBy('price','desc')->paginate(4);
    }


    public function category()
    {
       return $this->belongsTo('Category');
    }


    public function orders()
    {
        return $this->belongsToMany('Order');
    }

}