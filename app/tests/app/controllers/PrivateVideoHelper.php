<?php

class PrivateVideoHelper extends BaseController {


	public static function adpersvid() {
		$data = Input::all();
		$v = $data['v'];
		$t = $data['t'];
		if (!Auth::check()) return 2;
		
		$user_id = Auth::user()->user_id;
		$pv = PrivateVideo::where('user_id', '=', $user_id)->where('video_id', '=', $v)->get(array('id'))->toArray();
		if ($pv === false || !is_array($pv) || $pv == array()) {
			$has = false;
		} else {
			$has = true;
		}
		if ($t == 1) {
			if($has) {
				return 0;
			} else {
				$pvMod = new PrivateVideo();
				$pvMod->user_id = $user_id;
				$pvMod->video_id = $v;
				$pvMod->save();
				return 0;
			}
		} else {
			if($has) {
				$vMod = PrivateVideo::find($pv[0]['id']);
				$vMod->delete();
				return 1;
			} else {
				return 1;
			}		
		}
	}

	public static function getViewDatas($datas) {
		
		if(!Auth::check()) {#ekkor redirect to main page
			return Redirect::to("/");
		}
		
		$userId = Auth::user()->user_id;
		
		$datas['view'] = 'helper.proposer.default';
		$datas['styleCss']["URL::asset('css/proposer.css')"] = URL::asset('css/proposer.css');
		$datas['jsLinks']["URL::asset('js/proposer.js')"] = URL::asset('js/proposer.js');
		
		$videoNum = isset($datas['video_num']) ? $datas['video_num'] : 27;#
		$videoLinkPattern = isset($datas['video_link_pattern']) ? $datas['video_link_pattern'] : "/video/videoname/videoid";#
		$needMore = isset($datas['need_more']) ? $datas['need_more'] : 0;#
		$needPager = isset($datas['need_pager']) ? $datas['need_pager'] : 1;#
		$needMenu = isset($datas['need_menu']) ? $datas['need_menu'] : 0;#
		$moreLink = isset($datas['more_link']) ? $datas['more_link'] : ($needMore ? $proposerName : "");#
		$menuArray = isset($datas['menu_array']) ? $datas['menu_array'] : array();
		$needTitle = isset($datas['need_title']) ? $datas['need_title'] : 1;#
		$advPosPre = isset($datas['advPos']) ? explode(",", $datas['advPos']) : array(0,0,0,0,0);#
		$onlyFirst = isset($datas['onlyFirst']) ? $datas['onlyFirst'] : 0;
		$propClass = isset($datas['class']) ? $datas['class'] : "";
		
		
		$page = 1;
		if (isset($_GET['page']) && $needPager) {
			$page = $_GET['page'];
		}
		if ($needTitle == 0 || $needTitle > $page) {
			$needTitle = 0;
		} else {
			$needTitle = 1;
		}
		
		if ($onlyFirst && isset($_GET['page']) && $_GET['page'] > 1) {
			return false;
		}
		
		
		$datas['helperDataJson'] = 'helperDataJson';
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = "Personal video list";#
		}
		
		
		$idDatas = PrivateVideo::getListToFront($userId, $videoNum, $page);#need limit, page
		
		$randNumBase = count($idDatas['videos']) < $videoNum ? count($idDatas['videos']) : $videoNum;
		
		$advPos = array();
		$advPos2 = array();
		$rawAdvPos = isset($datas['advPos']) ? $datas['advPos'] : array(0,0,0,0,0);
		
		if ($rawAdvPos !== "") {
			if (count($advPosPre) < $randNumBase/2) {#count($advPosPre) < $randNumBase/2 #ekkor lehet szomszedosakat tiltani
				foreach($advPosPre as $ap) {
					$id = $ap;
					while($id == 0 || in_array($id, $advPos2) ) {
						$id = rand(2, $randNumBase+count($advPosPre));
					}
					$advPos[$id] = $id;
					$advPos2[$id] = $id;
					$advPos2[$id-1] = $id-1;
					$advPos2[$id+1] = $id+1;
 				}
			} else {
				$n = 0;
				$max = ceil($randNumBase/2);
				$ca = min($max, count($advPosPre));
				foreach($advPosPre as $ap) {
					if ($n < $max) {
						$id = $ap;
						while($id == 0 || in_array($id, $advPos) ) {
							$id = rand(2, $randNumBase+$ca);
						}
						$advPos[$id] = $id;
					}
					$n++;
				}
			}	
		}
		$advNum = count($advPos);#
		
		
		
		
		$datas['helperData']['class'] = $propClass;
		$datas['helperData']['proposer_id'] = 314;
		$datas['helperData']['videos_datas'] = $idDatas['videos'];
		
		$datas['helperData']['maxpage'] = ceil($idDatas['count']/$videoNum);#need videoNum
		$datas['helperData']['video_link_pattern'] = $videoLinkPattern;
		$datas['helperData']['adv_pos'] = $advPos;
		
		$datas['helperData']['video_num'] = count($idDatas['videos']) < $videoNum ? count($idDatas['videos']) : $videoNum;
		$datas['helperData']['adv_num'] = $advNum;
		
		$datas['helperData']['need_more'] = $needMore;
		$datas['helperData']['need_menu'] = $needMenu;
		$datas['helperData']['need_title'] = $needTitle;
		$datas['helperData']['need_pager'] = $needPager;
		$datas['helperData']['act_page'] = $page;
		
		$datas['helperData']['title'] = "Personal video list";
		
		$datas['helperData']['more_link'] = $moreLink;
		$datas['helperData']['pagerBase'] = "?";
		
		$datas['helperData']['menu_array'] = array();
		
		$out = '<meta name="description" content="Watch your favorite videos." />
<meta name="keywords" content="porn, sex,free porn, ruby, porn videos, sex videos, favorite videos, free pussy, pussy, adult entertainment" />
###prewlink###
###nextlink###';
		$out = str_replace('###prewlink###', '', $out);
		$out = str_replace('###nextlink###', '', $out);
		if ($datas['metaDatas'] == '') {
			$datas['metaDatas'] = $out;
		}
		return $datas;
	}
}