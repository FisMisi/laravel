<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Moodel szintek (gold, silver stb)
 */

class GsModellLevel extends Eloquent
{
	protected $table = 'gs_modell_levels';
	protected $primaryKey = 'id';
	
	 
        public static $rules = array(
            'title'           => 'required',
            'min_view'        => 'required|integer',
            'min_view_p_week' => 'required|integer',
            'min_rating'      => 'required|integer',
            'max_video_p_day' => 'required|integer',
            'pos'             => 'required|integer',
            );
        
    /**
    * Admin oldalon a modell szintek megjelenítése 
    * @return array() $query
    */ 
    public static function getModellLevels() 
    {
        $retArray = [
            'id',
            'title'
        ];
     
        $query = self::orderBy('id', 'desc')->get($retArray)->toArray();
        
        return $query;
    }

}
