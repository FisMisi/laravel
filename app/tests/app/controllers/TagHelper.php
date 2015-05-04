<?php

class TagHelper extends BaseController {

	public static function regentag() {
		DB::statement("UPDATE videos v SET active = subquery.active FROM ( SELECT max(i.active) as active, video_id FROM videos_i_tags vit JOIN internal_tags i ON (vit.internal_tag_id = i.internal_tag_id)  group by video_id) AS subquery WHERE v.video_id = subquery.video_id;");
		return Redirect::to("/administrator/tag");
	}

	public static function savetaggroup() {
		$datas = Input::all();
		if ($datas['tag_group_id'] == 0) {
			$group = new TagGroup();
		} else {
			$group = TagGroup::find($datas['tag_group_id']);
		}
		$group->tag_group_name = $datas['tag_group_name'];
		$group->pos = $datas['pos'];
		$group->active = $datas['active'];
		$group->save();
		return Redirect::to("/administrator/tag/mod/".Input::get('type')."__".$group->tag_group_id);
	}
	
	public static function saveinttag() {
		if (Input::get('internal_tag_id') == 0) {
			$it = new InternalTag();
			$needVideoUpdate = false;
		} else {
			$it = InternalTag::find(Input::get('internal_tag_id'));
			$old = $it->active;
			$needVideoUpdate = true;
		}
		
		if (!is_null(Input::get('pos'))) {
			$it->pos = Input::get('pos');
		}
		
		if (!is_null(Input::get('category_group'))) {
			$it->category_group = Input::get('category_group');
		}
		
		$it->internal_tag_name = Input::get('internal_tag_name');
		$it->internal_tag_seo_name = Input::get('internal_tag_seo_name');
		$it->active = Input::get('active');
		$it->save();
		if ($needVideoUpdate) {
			$it->updateVideosByITActive($old, $it->active);
		}
		return Redirect::to("/administrator/tag/mod/".Input::get('type')."__".$it->internal_tag_id);
	}
	
	public function deleteexternalfromint() {
		$internalTagId = Input::get('internal_tag_id');
		$externalTagId = Input::get('external_tag_id');
		$internalTag = InternalTag::find($internalTagId);
		$internalTag->removeFromExternal($externalTagId);
		$type = Input::get('type');
		$id = $type == 'int' ? $internalTagId : $externalTagId;
		return Redirect::to("/administrator/tag/mod/".$type."__".$id);
		
	}
	
	public function addexternaltoint() {
		$internalTagId = Input::get('internal_tag_id');
		$externalTagId = Input::get('external_tag_id');
		$internalTag = InternalTag::find($internalTagId);
		$internalTag->adsToExternalTag($externalTagId);
		$type = Input::get('type');
		$id = $type == 'int' ? $internalTagId : $externalTagId;
		return Redirect::to("/administrator/tag/mod/".$type."__".$id);
		
	}

	public static function subFunc($datas) {
		$datas['view'] = 'helper.admin.'.'tag'.'.modify';
		list($type, $id) = explode('__', $datas['id']);
			$datas['helperData']['tagId'] = $id;#internal-nal új esetén
		$datas['helperData']['group'] = 0;
		if ($type == 'gr') {
			$datas['helperData']['internal'] = 0;
			$datas['helperData']['group'] = 1;
			$datas['helperData']['Group'] = array();
			if ($id !== '0' && $id !== 0) {
				$datas['helperData']['Group'] = TagGroup::find($id)->toArray();
			}
		} elseif ($type == 'int') {
			$datas['helperData']['internal'] = 1;
			$datas['helperData']['Tag'] = InternalTag::where('internal_tag_id', '=', $id)->get(array('internal_tag_id', 'internal_tag_name', 'internal_tag_seo_name', 'active', 'see_count', 'pos', 'category_group'))->toArray();
			$datas['helperData']['externals'] = InternalTag::getExternalsToInternalId($id);
			$datas['helperData']['exttoadd'] = ExternalTag::getToAdminList(0, 0, 0, 0);
			$datas['helperData']['groups'] = TagGroup::getSelectDataToAdmin();
		} else {
			$datas['helperData']['internal'] = 0;
			$datas['helperData']['Tag'] = ExternalTag::getNeedableDataToModifyById($id);
			$datas['helperData']['internals'] = InternalTag::get(array('internal_tag_id', 'internal_tag_name'))->toArray();
		}
		return $datas;
	
	}

	public static function getAdminDatas($datas) {
		$internalList = 0;
		$isGroup = 0;
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['view'] = 'helper.admin.'.'tag'.'.list';
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = 'Tags';
		}
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		
		if (isset($datas['id'])) return self::subFunc($datas);
		
		$actualRoute = Route::getCurrentRoute()->getPath();
		$actualRouteElements = explode('/', $actualRoute);
		if (is_array($actualRouteElements) && in_array('group', $actualRouteElements)) $isGroup = 1;
		else if (is_array($actualRouteElements) && in_array('internal', $actualRouteElements)) $internalList = 1;
		$datas['helperData']['isgroups'] = $isGroup;
		$datas['helperData']['internalList'] = $internalList;
		if ($isGroup) {
			$datas['helperData']['groupList'] = TagGroup::get(array('tag_group_id', 'tag_group_name', 'pos', 'active'))->toArray();
		} else {
			
			$limit = 20;
			if(isset($_GET['limit'])) {
				$datas['helperData']['limit'] = $_GET['limit'];
				$limit = $_GET['limit'];
			} else {
				$datas['helperData']['limit'] = 20;
			}
			
			$page = 1;
			if(isset($_GET['page'])) {
				$datas['helperData']['page'] = $_GET['page'];
				$page = $_GET['page'];
			} else {
				$datas['helperData']['page'] = 1;
			}
			if ($internalList) {
			#internal_tags | active 0, 1, 2, 3 | internal_tag_name, internal_tag_seo_name, active, button
			
				
			
				$active = null;
				if (isset($_GET['active'])) {
					$active = $_GET['active'];
					$datas['helperData']['active'] = $_GET['active'];
					$query = InternalTag::where('active', '=', $active);
					$query2 = InternalTag::where('active', '=', $active);
					$query->take($limit);
					if ($page > 1) {
						$skip = ($page-1)*$limit;
						$query->skip($skip);
					}
					$query->orderBy('pos');
					$query->orderBy('internal_tag_name');
					$count = $query2->count();
					$datas['helperData']['tagList'] = $query->get(array('internal_tag_id', 'internal_tag_name', 'internal_tag_seo_name', 'active', 'see_count', 'pos'))->toArray();
					$datas['helperData']['needPager'] = $count/$limit > 1 ? 1 : 0;
					$datas['helperData']['pageOptions'] = ceil($count/$limit);
				} else {
					$datas['helperData']['active'] = 3;
					$query = InternalTag::take($limit);
					if ($page > 1) {
						$skip = ($page-1)*$limit;
						$query->skip($skip);
					}
					$query->orderBy('pos');
					$query->orderBy('internal_tag_name');
					$count = InternalTag::count();
					$datas['helperData']['needPager'] = $count/$limit > 1 ? 1 : 0;
					$datas['helperData']['pagerOptions'] = ceil($count/$limit);
					$datas['helperData']['tagList'] = $query->get(array('internal_tag_id', 'internal_tag_name', 'internal_tag_seo_name', 'active', 'see_count', 'pos'))->toArray();
				}
			
			
			} else {
			#external_tags | partner_id, has_internal | external_tag_name, partner.partner_name, button
				$partner = 0;
				if (isset($_GET['partner'])) {
					$partner = $_GET['partner'];
					$datas['helperData']['partner'] = $_GET['partner'];
				} else {
					$datas['helperData']['partner'] = 0;
				}
				$hi = 2;
				if (isset($_GET['hi'])) {
					$hi = $_GET['hi'];
					$datas['helperData']['hi'] = $_GET['hi'];
				} else {
					$datas['helperData']['hi'] = 2;
				}
				
				$limit = 20;
				if (isset($_GET['limit'])) {
					$datas['helperData']['limit'] = $_GET['limit'];
					$limit = $_GET['limit'];
				} else {
					$datas['helperData']['limit'] = 20;
				}
				
				$page = 1;
				if (isset($_GET['page'])) {
					$datas['helperData']['page'] = $_GET['page'];
					$page = $_GET['page'];
				} else {
					$data['helperData']['page'] = 1;
				}
				$idDatas = ExternalTag::getToAdminList($partner, $hi, $limit, $page);
				$datas['helperData']['tagList'] = $idDatas['tags'];
				$datas['helperData']['needPager'] = $idDatas['count']/$limit > 1 ? 1 : 0;
				$datas['helperData']['pagerOptions'] = ceil($idDatas['count']/$limit);
				$datas['helperData']['partnerList'] = Partner::get(array('partner_id', 'partner_name'))->toArray();
				
			}
		}
		
		return $datas;
	}
}