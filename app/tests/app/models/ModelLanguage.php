<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * kapcsolótábla a gs_languages és a models táblák között
 */

class ModelLanguage extends Eloquent
{
	protected $table = 'model_language';
	protected $primaryKey = 'id';
	public    $timestamps = false;
	 
        public static $rules = array(
            'gs_language_id'  => 'required|integer',
            'model_id'        => 'required|integer',
            );
}