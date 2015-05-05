<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
class ExternalCategory extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'external_categories';
	protected $primaryKey = 'external_category_id';
	
	public static function hasCategory($categoryName) {
		$names = self::where('active', 1)->where('actualname', '=', $categoryName)->get(array('external_category_id'))->toArray();
		if (count($names)) {
			return true;
		}
		return false;
	}
	
	public static function getNotIn($categoryNameArray) {
		return self::where('active', 1)->whereNotIn('actualname', $categoryNameArray)->get(array('actualname'))->toArray();
		
	}
	
	public static function getActiveCategoryNames() {
		return self::where('active', 1)->get(array('actualname'))->toArray();
	}
	
}