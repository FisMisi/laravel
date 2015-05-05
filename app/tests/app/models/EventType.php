<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class EventType extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'event_types';
	protected $primaryKey = 'event_type_id';
	
	
	public static function getEventTypeToList($withAll = true) {
		$query = self::join('events', 'events.event_type_id', '=', 'event_types.event_type_id');
		$list = $query->get(array('events.event_type_id', 'title'))->toArray();
		if ($withAll) {
			$ret = array(0 => 'All');
		} else {
			$ret = array();
		}
		foreach($list as $l) {
			$ret[$l['event_type_id']] = $l['title'];
		}
		return $ret;
	}
	
}