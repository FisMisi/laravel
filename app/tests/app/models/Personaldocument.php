<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

 /**
 *Modelleknek lehetőségük van igazoló okmányok feltöltésére
  * ezen okmányok útvonalának tárolására létrehozott tábla 
 */ 

class Personaldocument extends Eloquent 
{
    public $timestamps = false;	
    protected $table = 'personaldocuments';

    protected $primaryKey = 'id';
    
}
