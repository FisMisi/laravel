<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Mainmenu extends Eloquent implements RemindableInterface {

	use RemindableTrait;

	protected $table = 'mainmenus';
	protected $primaryKey = 'mainmenu_id';
}