<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Tárolt videók (model videok és belső videók) típusainak tárolására szolgáló tábla
 */

class StoragedVideoType extends Eloquent
{
	protected $table = 'storaged_video_types';
	protected $primaryKey = 'id';
	
	 
        public static $rules = array(
            'name'  => 'required',
            'title' => 'required',
            );
}