<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class RightUser extends Eloquent implements RemindableInterface {

	use RemindableTrait;

	protected $table = 'users_rights';
	protected $primaryKey = 'ur_id';
	
	public static function getUserIdsToRight($right) {
		$right = Right::where('right_name', 'like', $right)->first();
		$rus = self::where('right_id', $right->right_id)->get();
		$ret = array();
		foreach($rus as $ru) {
			$ret[] = $ru->user_id;
		}
		return $ret;
	}
}