<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class VideoSeeN extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'see_videos_n';
	protected $primaryKey = 'see_video_id';

	
}