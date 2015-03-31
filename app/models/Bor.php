<?php

class Bor extends Eloquent 
{
    //protected $fillable = ['name', 'price', 'image', 'availability', 'category_id'];
    protected $table = 'borok';
    public    $timestamps = true;
   
    
    public static $rules = [
        "megnevezes"                 => array('required'),
        "user_id"                    => array('required','numeric'),
        "borszin_id"                 => array('required','integer'),
        "bor_cukor_id"               => array('integer'),
        "bor_kategoria_keszites_id"  => array('integer'),
        "bor_kategoria_minosites_id" => array('integer'),
        "leiras"                     => array('min:3'),
        "evjarat"                    => array('integer'),
        "alkohol"                    => array('integer'),
        "cukor"                      => array('integer'),
        "sav"                        => array('integer'),
        "cukormentes_ext"            => array('integer'),
        "csersav"                    => array('integer'),
        "glicerin"                   => array('integer'),
        "szenhidrat"                 => array('integer'),
        "zsir"                       => array('integer'),
        "viz"                        => array('integer'),
        "fogyasztasi_fok"            => array('min:2'),
        "leltari_mennyiseg"          => array('integer'),
        "ar"                         => array('integer'),
        "kep"                        => array('required','image','mimes:jpeg,jpg,bmp,png,gif'),
        "megjegyzes"                 => array('min:3'),
        "active_admin"               => array('integer'),
        "active_user"                => array('integer'),
    ];
    
    /*
     * Saját bork kínálat kezelése
     * @param user_id 
     */
    public static function getMyWines($userId)
    {
        $query = self::where('user_id', '=', $userId)->get();
        
        
        return $query;
    }
}