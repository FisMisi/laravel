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
    
    public static function getQuery()
    {
        $query = self::join('categories', 'menuitems.category_id', '=', 'categories.id');
        
        return $query->take(10)->paginate(5, array(
                'menuitems.name as product_name',
                'menuitems.price as product_price',
                'menuitems.availability as product_availability',
                'categories.name as categ_name',
            ));
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