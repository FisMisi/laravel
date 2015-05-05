<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Right extends Eloquent implements RemindableInterface {

	use RemindableTrait;

	protected $table = 'rights';
	protected $primaryKey = 'right_id';
}