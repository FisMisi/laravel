<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Country extends Eloquent 
{
    protected $table = 'countries';
    protected $primaryKey = 'country_id';


    protected $fillable = [];
	
}
