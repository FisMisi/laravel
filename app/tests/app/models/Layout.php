<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Layout extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'layouts';
	protected $primaryKey = 'layout_id';


}