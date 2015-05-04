<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class VideosStars extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'videos_stars';
	protected $primaryKey = 'videos_stars_id';
	
}