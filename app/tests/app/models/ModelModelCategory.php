<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

 /**
  * Modellek bekategórizásálásához szükséges kapcsoló tábla 
  * 
 */ 

class ModelModelCategory extends Eloquent 
{
    protected   $table = 'model_model_category';

    protected $primaryKey = 'id';
    
    public static $rules = [
        'model_id'    => 'required|integer',
        'type_id'     => 'required|integer',
        'category_id' => 'integer|integer'
    ];
    
   
    /**
     * Model regisztrációja step2 kategória rules és messages. 
     *
     * @return array()
     */
    public static function getCategoryRules($types)
    {
        $newMessages = [];
        $newRules    = [];
        
        foreach ($types as $type)
        {
            $rule = [
                $type['id'] => 'required'
            ];
            
            $message = [
                $type['id'].".required" => "Please choose " . $type[title] . " element(s)",
            ];
            
            $newMessages = $newMessages + $message;
     
            $newRules = $newRules + $rule;
        }
        
        return [
                'newRules'    => $newRules,
                'newMessages' => $newMessages
              ];
    }
   
}
