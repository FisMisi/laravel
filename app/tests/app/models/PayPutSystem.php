<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class PayPutSystem extends Eloquent implements RemindableInterface 
{
    use RemindableTrait;
    
    protected $table = 'payput_system';
    protected $primaryKey = 'pos_id';

    public static function getActive()
    {
        return self::where('active','=',1)->get();
    }
}
