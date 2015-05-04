<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Tárolt videók (model videok és belső videók) nézetségének nyilvántartása
 */

class StoragedSeeVideo extends Eloquent
{
	protected $table = 'storaged_see_videos';
	protected $primaryKey = 'id';
	
	 
        public static $rules = array(
            'storaged_video_id'   => 'required|integer',   
            'see_count'           => 'required|integer'
            );
}