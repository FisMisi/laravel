<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
class ExternalTag extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'external_tags';
	protected $primaryKey = 'external_tag_id';
	
	public static function hasTag($tagName) {
		$tags = self::where('active', 1)->where('external_tag_name', '=', $tagName)->get(array('external_tag_id'))->toArray();
		if (count($tags)) return true;
		return false;
	}
	
	public static function getNotIn($inArray, $partnerId) {
		return self::where('active', 1)->whereNotIn('external_tag_name', $inArray)->where('partner_id', '=', $partnerId)->get(array('external_tag_name'))->toArray();
	}
	
	public static function reformatTag($tagName){
		$tagName = str_replace(' ', '', $tagName);
		$tagName = str_replace("amp;", '', $tagName);
		$tagName = str_replace("&", 'and', $tagName);
		$tagName = str_replace("/", 'and', $tagName);
		return strtolower(str_replace('"', '', $tagName));
	
	}
	
	public static function getToAdminList($partner, $hi, $limit = 20, $page = 1) {
		
		if ($page > 0) {
			$page--;
		}
		
		$query = self::join('partners', 'partners.partner_id', '=', 'external_tags.partner_id');
		$query2 = self::join('partners', 'partners.partner_id', '=', 'external_tags.partner_id');
		$query->leftJoin('i_e_tags', function($join) {
				$join->on('i_e_tags.external_tag_id', '=', 'external_tags.external_tag_id');
			});
		$query2->leftJoin('i_e_tags', function($join) {
				$join->on('i_e_tags.external_tag_id', '=', 'external_tags.external_tag_id');
			});
		$query->leftJoin('internal_tags', 'internal_tags.internal_tag_id', '=', 'i_e_tags.internal_tag_id');
		$query2->leftJoin('internal_tags', 'internal_tags.internal_tag_id', '=', 'i_e_tags.internal_tag_id');
		if($partner != 0) {
			$query->where('external_tags.partner_id', '=', $partner);
			$query2->where('external_tags.partner_id', '=', $partner);
		}
		
		if ($hi == 0) {
			$query->whereNull('internal_tag_name');
			$query2->whereNull('internal_tag_name');
		} else if ($hi == 1) {
			$query->whereNotNull('internal_tag_name');
			$query2->whereNotNull('internal_tag_name');
		}
		if ($limit != 0) {
			$query->take($limit);
			if ($page) {
				$query->skip($page*$limit);
			}
		} else {
			$query->orderBy('external_tag_name');
			$query->orderBy('external_tags.partner_id');
			return $query->get(array('external_tags.external_tag_id', 'partner_name', 'external_tag_name', 'internal_tag_name'))->toArray();
		}
		$ret = array();
		$query->orderBy('external_tag_name');
		$query->orderBy('external_tags.partner_id');
		$ret['tags'] = $query->get(array('external_tags.external_tag_id', 'partner_name', 'external_tag_name', 'internal_tag_name'))->toArray();
		$ret['count'] = $query2->count();
		
		
		return $ret;
		
	}
	
	public static function getNeedableDataToModifyById($id) {
		$query = self::join('partners', 'partners.partner_id', '=', 'external_tags.partner_id');
		$query->leftJoin('i_e_tags', 'i_e_tags.external_tag_id', '=', 'external_tags.external_tag_id');
		$query->leftJoin('internal_tags', 'internal_tags.internal_tag_id', '=', 'i_e_tags.internal_tag_id');
		$query->where('external_tags.external_tag_id', '=', $id);
		return $query->get(array('external_tags.external_tag_id', 'external_tag_name', 'external_tags.partner_id', 'partner_name', 'i_e_tag_id', 'internal_tags.internal_tag_id', 'internal_tag_name'))->toArray();
	}
	
	
	
	
	
	
	
	
	
	
}