<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Routing extends Eloquent implements RemindableInterface {

	use RemindableTrait;

	protected $table = 'routings';
	protected $primaryKey = 'id';
	
}