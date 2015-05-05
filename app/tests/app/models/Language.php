<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Language extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'languages';
	protected $primaryKey = 'language_id';
	
	
	public static function getLongToShort($short) {
		$ret = self::where('active', 1)->where('short', 'like', $short)->first();
		if (is_null($ret)) return false;
		return $ret->long;
	}
	
	public static function isValidLanguage($short) {
		$lang = self::where('active', 1)->where('short', 'like', $short)->first();
		if (is_null($lang)) return false;		
		return true;
	}
	
	public static function getLangShortList($setKey = false) {
		$lang = self::where('active', 1)->orderBy('language_id')->get();
		$ret = array();
		if (!$setKey) {
			foreach($lang as $l) {
				$ret[] = $l->short;
			}
		} else {
			foreach($lang as $l) {
				$ret[$l->short] = $l->short;
			}
		
		}
		return $ret;
	}
	
	public static function getLangList($needAll = true) {
		$lang = self::where('active', 1)->get();
		$ret = array();
		if ($needAll) $ret['no'] = "All";
		foreach($lang as $l) {
			$ret[$l->short] = $l->long;
		}
		return $ret;
	}
	
}
	
