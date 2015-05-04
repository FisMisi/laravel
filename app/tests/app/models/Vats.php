<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Vats extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'vats';
	protected $primaryKey = 'id';
	
}