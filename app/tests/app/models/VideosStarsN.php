<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class VideosStarsN extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'videos_stars_n';
	protected $primaryKey = 'videos_stars_id';
	
}