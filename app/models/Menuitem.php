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
    
    public static $update_rules = [
        "name"        => array('required','max:30'),
        "price"       => array('required','numeric'),
        "category_id" => array('required','integer'),
        "image"       => array('image','mimes:jpeg,jpg,bmp,png,gif'),
        "availability"=> 'integer'
    ];
    
    public static function getQuery($availability, $type)
    {
        $query = self::join('categories', 'menuitems.category_id', '=', 'categories.id');
        
        if($availability != 2){
            $query = $query->where('menuitems.availability', '=', $availability); 
        }
        
        if($type != 0){
            $query = $query->where('menuitems.category_id', '=', $type); 
        }
        
        return $query->paginate(6, array(
                'menuitems.id as menuitem_id',
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