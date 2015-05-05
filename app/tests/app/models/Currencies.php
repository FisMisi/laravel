<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Currencies extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'currencies';
	protected $primaryKey = 'id';
	
}