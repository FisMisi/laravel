<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class BannerType extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'banner_types';
	protected $primaryKey = 'banner_type_id';
	
	
	public static function getDatasToAll($active = 2, $limit = 20, $page = 1) {
		
		if($page > 0) {
			$page--;
		}
		$ret = array();
		if ($active === 0 || $active === '0' || $active == 1) { 
			$query = self::where('active', $active)->take($limit);
			if ($page > 0) {
				$query->skip($page*$limit);
			}
			$ret['banners'] = $query->get();
			$ret['count'] = self::where('active', $active)->count();
		} else { 
			$query = self::take($limit);
			if ($page > 0) {
				$query->skip($page*$limit);
			}
			$ret['banners'] = $query->get();
			$ret['count'] = self::count();
		}
		return $ret;
	}


}