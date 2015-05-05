<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class GsConfig extends Eloquent implements RemindableInterface 
{
    use RemindableTrait;

    protected $table = 'gs_configs';
    protected $primaryKey = 'id';

    public static $rules = [
        'value' => 'required'
    ];
        	
}