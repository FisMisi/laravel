<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class LogEvent extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'events';
	protected $primaryKey = 'event_id';
	
	public static function getDatasToAll($eventType = 0, $hasUser = 2, $limit = 20, $page = 1) {
		if ($page > 0) {
			$page--;
		}
		if (is_null($eventType)) {
			$eventType = 0;
		}
		if (is_null($hasUser)) {
			$hasUser = 2;
		}
		
		$query = self::join('event_types', 'event_types.event_type_id', '=', 'events.event_type_id');
		$query2 = self::join('event_types', 'event_types.event_type_id', '=', 'events.event_type_id');
		$query->leftJoin('users', function($join) {
				$join->on('users.user_id', '=', 'events.user_id');
		});
		$query2->leftJoin('users', function($join) {
				$join->on('users.user_id', '=', 'events.user_id');
		});
		if ($hasUser == '0') {
			$query2->whereNull('events.user_id');
			$query->whereNull('events.user_id');
		} else if ($hasUser == 1) {
			$query2->whereNotNull('events.user_id');
			$query->whereNotNull('events.user_id');
		}
		if ($eventType != '0') {
			$query2->where('events.event_type_id', $eventType);
			$query->where('events.event_type_id', $eventType);
		}
		$query->take($limit);
		if ($page > 0) {
			$query->skip($page*$limit);
		}
		
		
		$ret = array();
		$ret['events'] = $query->get(array('title', 'entity_name', 'entity_id', 'session_id', 'email', 'events.created_at'));
		$ret['count'] = $query2->count();
		return $ret;
	}
	
	public static function addEvent($datas) {
	
		$event = new self;
		$event->session_id = Session::getId();
		$event->user_id = Auth::check() ? Auth::user()->user_id : null;
		
		$event->entity_name = $datas['entityName'];
		$event->entity_id = $datas['entityId'];
		
		if (is_numeric($datas['eventType'])) {
			$event->event_type_id = $datas['eventType'];
		} else {
			$eventType = EventType::where('name', 'like', $datas['eventType'])->first();
			$event->event_type_id = $eventType->event_type_id;
		}
		$event->save();
	}
}