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
    
    
     /**
     * Model regisztrációja step2 kategória rules és messages. 
     * @param array() - title,id
     * @return array()
     */
    public static function getCategoryRules($types)
    {
        $newMessages = [];
        $newRules    = [];

        foreach ($types as $type)
        {
            $message = [
                $type['id'].".required" => "Please choose " . $type['title'] . " element(s)",
            ];

            $newMessages = $newMessages + $message;

            $rule = [
                $type['id'] => 'required'
            ];

            $newRules = $newRules + $rule;
        }

        return [
                'newRules'    => $newRules,
                'newMessages' => $newMessages
              ];
    }
    
 
}