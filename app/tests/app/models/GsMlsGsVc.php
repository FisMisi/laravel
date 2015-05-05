<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Gift Show Modell Level és Gs Video Category tábla kapcsolója
 */

class GsMlsGsVc extends Eloquent
{
    protected $table = 'gs_mls_gs_vc';
    protected $primaryKey = 'id';


    public static $rules = array(
        'gs_model_level_id'     => 'required|integer',
        'gs_video_category_id'  => 'required|integer',
        'is_exclusive'          => 'required|integer',
        'min'                   => 'required|integer', //min price
        'max'                   => 'required|integer', //max price
        'referenced_price'      => 'required|integer', //ajánlott ár
    );
        
    /**
     * Admin categories prices rules és messages. 
     * @param array(), $levels - $id,$title
     * @return array(), newMessages, newRules
     */
    public static function getRules($levels)
    {
        $newMessages = [];
        $newRules    = [];
        
        foreach ($levels as $level)
        {
            //egyedi szabályok generálása
            $rule = [
                
                'min__0__'.$level['id']              => 'required|integer|max:'.Input::get('max__0__'.$level['id']),
                'max__0__'.$level['id']              => 'required|integer|min:'.Input::get('min__0__'.$level['id']),
                'referenced_price__0__'.$level['id'] => 'required|integer|max:'.Input::get('max__0__'.$level['id']),
                
                'min__1__'.$level['id']              => 'required|integer|max:'.Input::get('max__1__'.$level['id']),
                'max__1__'.$level['id']              => 'required|integer|min:'.Input::get('min__1__'.$level['id']),
                'referenced_price__1__'.$level['id'] => 'required|integer|max:'.Input::get('max__1__'.$level['id']),
                
            ];
            
            //egyedi üzenetek generálása
            $message = [
                'min__0__'.$level['id'].".required"              => 'The '.$level['title'] .' level minimum field is required in prefabricated video prices!',
                'min__0__'.$level['id'].".max"                   => 'The '.$level['title'] .' minimum field value may not be greater than maximum field in prefabricated video prices!',  
                'max__0__'.$level['id'].".required"              => 'The '.$level['title'] .' level maximum field is required in prefabricated video prices!',
                'max__0__'.$level['id'].".min"                   => 'The '.$level['title'] .' maximum field value may not be shorter than minimum field in prefabricated video prices!',
                'referenced_price__0__'.$level['id'].".required" => 'The '.$level['title'] .' level referenced field is required in prefabricated video prices!',
                'referenced_price__0__'.$level['id'].".max"      => 'The '.$level['title'] .' level referenced field value may not be greater than maximum field in prefabricated video prices!',
                
                
                
                
                'max__1__'.$level['id'].".required"              => 'The '.$level['title'] .' level maximum field is required in exclusive video prices!',
                'max__1__'.$level['id'].'.min'                   => 'The '.$level['title'] .' maximum field value may not be shorter than minimum field video prices!',    
                'min__1__'.$level['id'].".required"              => 'The '.$level['title'] .' level minimum field is required in exclusive video prices!',
                'min__1__'.$level['id'].".max"                   => 'The '.$level['title'] .' minimum field value may not be greater than maximum field in video prices!',
                'referenced_price__1__'.$level['id'].".required" => 'The '.$level['title'] .' level referenced field is required in exclusive video prices!',   
                'referenced_price__1__'.$level['id'].".max"      => 'The '.$level['title'] .' level referenced field value may not be greater than maximum field in in video prices!',
           
            ];
            
            $newMessages = $newMessages + $message;
    
            $newRules = $newRules + $rule;
        }
        
        return [
                'newRules'    => $newRules,
                'newMessages' => $newMessages
              ];
    }
    
    /**
    * Public oldalon a modell szintjének megfelelő árak behúzása
    * @param int $modelLevelId szint id
    * @return array() $query
    */ 
    public static function getVideoCategoryPrice($modelLevelId) 
    {
        $query = self::where('gs_model_level_id', '=', $modelLevelId)->get()->toArray();
        
        $ret = array();
        
        foreach($query as $data) {
            $ret[$data['is_exclusive']][$data['gs_video_category_id']]['min']              = $data['min'];
            $ret[$data['is_exclusive']][$data['gs_video_category_id']]['max']              = $data['max'];
            $ret[$data['is_exclusive']][$data['gs_video_category_id']]['referenced_price'] = $data['referenced_price'];
        }
        
        return $ret;
        
    }
}