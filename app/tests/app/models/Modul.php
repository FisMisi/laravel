<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Modul extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'modules';
	protected $primaryKey = 'modul_id';


}