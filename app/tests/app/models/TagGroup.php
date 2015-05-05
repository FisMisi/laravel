<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
class TagGroup extends Eloquent implements RemindableInterface {
	use RemindableTrait;

	protected $table = 'tag_groups';
	protected $primaryKey = 'tag_group_id';

	public static function getSelectDataToAdmin() {
		$ret = array();
		$idData = self::where('active', '=', 1)->get(array('tag_group_id', 'tag_group_name'))->toArray();
		$ret[0] = "";
		foreach($idData as $id) {
			$ret[$id['tag_group_id']] = $id['tag_group_name'];
		}
		return $ret;
	}
	
	public static function getCategoriesToHeader() {
	
		$allGroup = self::where('active', '=', 1)->orderBy('pos')->get(array('tag_group_id', 'tag_group_name', 'pos'))->toArray();
		$ret = array();
		foreach($allGroup as $group) {
			$id = array();
			$id['groupname'] = $group['tag_group_name'];
			$id['category'] = InternalTag::where('active', '=', 1)->where('category_group', '=', $group['tag_group_id'])->orderBy('pos')->get(array('internal_tag_id', 'internal_tag_name'))->toArray();
			$ret[$group['pos']] = $id;
		}
		return $ret;
	}
}