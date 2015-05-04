<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Content extends Eloquent implements RemindableInterface {

	use RemindableTrait;

	protected $table = 'contents';
	
	protected $primaryKey = 'content_id';
	
}