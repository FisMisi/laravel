<?php

class ProposerHelper extends BaseController {


	public static function proposersave() {
		#var_dump(Input::all());
		$input = Input::all();
		
		if($input['name'] == "" || $input['title'] == "" ) return Redirect::to("administrator/proposer/".$input['proposer_type_id']);
		
		if ($input["proposer_type_id"] == "0") {#ekkor insert
			$proposer = new Proposer();
		} else {
			$proposer = Proposer::find($input["proposer_type_id"]);
		}
			$proposer->name = $input['name'];
			$proposer->title = $input['title'];
			
			$sep = "";
			$whereSQL = "";#ezt meg ki kell talalni
			$whereJson = array();
			#WhereGenerate
			if(!isset($input['alltag']) || $input['alltag'] != '1') {
				#ekkor nem az osszeset kerjuk le, kell where-t generalni
				$needTags = array();
				foreach($input as $key => $value) {
					if(strpos($key, 'wt') !== false) {#ekkor az a tag
						$needTags[] = $value;
					}
				}
				if (count($needTags) == 1) {#ekkor =
					$id = array();
					$id[0] = "=";
					$id[1] = $needTags[0];
					$whereSQL.=$sep."videos_i_tags.internal_tag_id".$id[0].$id[1];
					$sep = " and ";
					$whereJson['tag'] = $id;
				} else if (count($needTags) > 1) {#ekkor IN
					$id[0] = " IN ";
					$id[1] = $needTags;
					$whereSQL.=$sep."videos_i_tags.internal_tag_id".$id[0]."(".implode(",", $id[1]).")";
					$sep = " and ";
					$whereJson['tag'] = $id;
				}
			}
			
			if (!isset($input['allrating']) || $input['allrating'] != '1') {#ekkor kell rating
				$id = array();
				$id[0] = $input['rw2'];
				$id[1] = $input['rw1'];
				$whereJson['rating'] = $id;
				$whereSQL.= $sep."(videos.rating)".($id[1] == 'b' ? " > " : " < ").$id[0];
				$sep = ' AND ';
			}
			
			if (!isset($input['alllength']) || $input['alllength'] != '1') {#ekkor kell length
				if(
					!isset($input['minh']) || is_null($input['minh']) || 
					!isset($input['minm']) || is_null($input['minm']) || 
					!isset($input['mins']) || is_null($input['mins']) || 
					
					!isset($input['maxh']) || is_null($input['maxh']) || 
					!isset($input['maxm']) || is_null($input['maxm']) || 
					!isset($input['maxs']) || is_null($input['maxs']) 
				) return Redirect::to("administrator/proposer/".$input['proposer_type_id']);
				substr($input['minh'], -2);
				$min = 	str_pad(substr($input['minh'], -2), 2, '0', STR_PAD_LEFT).":".
						str_pad(substr($input['minm'], -2), 2, '0', STR_PAD_LEFT).":".
						str_pad(substr($input['mins'], -2), 2, '0', STR_PAD_LEFT);
				$max = 	str_pad(substr($input['maxh'], -2), 2, '0', STR_PAD_LEFT).":".
						str_pad(substr($input['maxm'], -2), 2, '0', STR_PAD_LEFT).":".
						str_pad(substr($input['maxs'], -2), 2, '0', STR_PAD_LEFT);
				$whereSQL.= $sep."videos.length between '".$min."' and '".$max."'";
				$sep = ' AND ';
				$id = array();
				$id[0] = $min;
				$id[1] = $max;
				$whereJson['length'] = $id;
			
			}
			$proposer->where_json_data =json_encode($whereJson);
			$proposer->where_sql = $whereSQL;
			#OrderGenerate
			$sep2 = "";
			$orderSQL = "";
			$orderJson = array();
			if(isset($input['ou1']) && $input['ou1'] == '1') {
				$id[0] = $input['o1e'];
				$id[1] = $input['o1d'];
				$orderSQL.= $sep2.$id[0]." ".$id[1];
				$sep2 = ", ";
				$orderJson[] = $id; 
			}
			
			if (isset($input['ou2']) && $input['ou2'] == '1') {
				$id[0] = $input['o2e'];
				$id[1] = $input['o2d'];
				$orderSQL.= $sep2.$id[0]." ".$id[1];
				$sep2 = ", ";
				$orderJson[] = $id; 
			}
			
			if (isset($input['ou3']) && $input['ou3'] == '1') {
				$id[0] = $input['o3e'];
				$id[1] = $input['o3d'];
				$orderSQL.= $sep2.$id[0]." ".$id[1];
				$sep2 = ", ";
				$orderJson[] = $id; 
			}
			
			$proposer->order_json_data = json_encode($orderJson);
			$proposer->order_sql = $orderSQL;
			$proposer->save();
			 return Redirect::to("administrator/proposer/".$proposer->proposer_type_id);
			
		 
	}


	public static function subFunc($datas) {
		$datas['view'] = 'helper.admin.'.'proposer'.'.modify';
		$datas['helperData']['tags'] = InternalTag::where('active', '=', '1')->get(array('internal_tag_id', 'internal_tag_name'))->toArray();
		#var_dump($datas['helperData']['tags']);
		#die();
		if ($datas['id'] == 0) {
			$datas['helperData']['new'] = 1;
		} else {
			$datas['helperData']['new'] = 0;
			$proposer = Proposer::find($datas['id']);
			
			$datas['helperData']['proposer']['name'] = $proposer->name;
			$datas['helperData']['proposer']['title'] = $proposer->title;
			$datas['helperData']['proposer']['proposer_type_id'] = $proposer->proposer_type_id;
			$whereJson = $proposer->where_json_data;
			$whereObj = json_decode($whereJson);
			$orderJson = $proposer->order_json_data;
			$orderObj = json_decode($orderJson);
			#set Where Datas
			if (is_null($whereObj) || $whereObj == array()) {
				$datas['helperData']['alltag'] = 1;
				$datas['helperData']['allrating'] = 1;
				$datas['helperData']['alllength'] = 1;
				$datas['helperData']['tagsv'] = array();
				$datas['helperData']['rw1'] = 'k';
				$datas['helperData']['rw2'] = "";
				
				$datas['helperData']['minh'] = "0";
				$datas['helperData']['minm'] = "00";
				$datas['helperData']['mins'] = "01";
				
				$datas['helperData']['maxh'] = "9";
				$datas['helperData']['maxm'] = "59";
				$datas['helperData']['maxs'] = "59";
			} else {
				if (isset($whereObj->tag)) {
						$datas['helperData']['alltag'] = 0;
						$datas['helperData']['tagsv'] = $whereObj->tag[0] == '=' ? array($whereObj->tag[1]) : $whereObj->tag[1];
				} else {
					$datas['helperData']['alltag'] = 1;
					$datas['helperData']['tagsv'] = array();
				}
				if (isset($whereObj->rating)) {
					$datas['helperData']['allrating'] = 0;
					$datas['helperData']['rw1'] = $whereObj->rating[1];
					$datas['helperData']['rw2'] = $whereObj->rating[0];
				} else {
					$datas['helperData']['allrating'] = 1;
					$datas['helperData']['rw1'] = 'k';
					$datas['helperData']['rw2'] = "";
				}
				if (isset($whereObj->length)) {
					$datas['helperData']['alllength'] = 1;
					
					$min = $whereObj->length[0];
					$max = $whereObj->length[1];
					
					list($minh,$minm,$mins) = explode(":", $min);
					list($maxh,$maxm,$maxs) = explode(":", $max);
					
					
					$datas['helperData']['minh'] = $minh;
					$datas['helperData']['minm'] = $minm;
					$datas['helperData']['mins'] = $mins;
					
					$datas['helperData']['maxh'] = $maxh;
					$datas['helperData']['maxm'] = $maxm;
					$datas['helperData']['maxs'] = $maxs;
				} else {
					$datas['helperData']['alllength'] = 0;
					$datas['helperData']['minh'] = "0";
					$datas['helperData']['minm'] = "00";
					$datas['helperData']['mins'] = "01";
					
					$datas['helperData']['maxh'] = "9";
					$datas['helperData']['maxm'] = "59";
					$datas['helperData']['maxs'] = "59";
				}
			}
			#set Order Datas
			if (is_null($orderObj) || $orderObj == array()) {
				$datas['helperData']['ou1'] = 0;
				$datas['helperData']['ou2'] = 0;
				$datas['helperData']['ou3'] = 0;
				$datas['helperData']['o1e'] = -1;
				$datas['helperData']['o1d'] = -1;
				$datas['helperData']['o2e'] = -1;
				$datas['helperData']['o2d'] = -1;
				$datas['helperData']['o3e'] = -1;
				$datas['helperData']['o3d'] = -1;
			} else {
				if (isset($orderObj[0])) {
					$datas['helperData']['ou1'] = 1;
					$datas['helperData']['o1e'] = $orderObj[0][0];
					$datas['helperData']['o1d'] = $orderObj[0][1];
				} else {
					$datas['helperData']['ou1'] = 0;
					$datas['helperData']['o1e'] = -1;
					$datas['helperData']['o1d'] = -1;
				}
				
				if (isset($orderObj[1])) {
					$datas['helperData']['ou2'] = 1;
					$datas['helperData']['o2e'] = $orderObj[1][0];
					$datas['helperData']['o2d'] = $orderObj[1][1];
				} else {
					$datas['helperData']['ou2'] = 0;
					$datas['helperData']['o2e'] = -1;
					$datas['helperData']['o2d'] = -1;
				}
				
				if (isset($orderObj[2])) {
					$datas['helperData']['ou3'] = 1;
					$datas['helperData']['o3e'] = $orderObj[2][0];
					$datas['helperData']['o3d'] = $orderObj[2][1];
				} else {
					$datas['helperData']['ou3'] = 0;
					$datas['helperData']['o3e'] = -1;
					$datas['helperData']['o3d'] = -1;
				}
			}
		}
		return $datas;
	}
	public static function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	/**
	 * datas need: proposerId(id, nullable), limit(video_num, nullable), page(page, nullable, first is 0)
	 *   videoLinkPattern(video_link_pattern, nullable)
	 */
	public static function generateDatasToPublicView($datas = array()) {
	
		if (isset($datas['id'])) {
			$proposerId = $datas['id'];
		} else {
			$proposerId = Session::get('proposerPublic.proposerId', function() {  
				$first = Proposer::where('proposer_name', '=', 'new')->first();
				$return = $first->proposer_type_id;
			});
		}
		
		if (isset($datas['video_num'])) {
			$limit = $datas['video_num'];
		} else {
			$limit = Session::get('proposerPublic.limit', 16);
		}
		
		if(isset($datas['page'])) {
			$page = $datas['page'];
		} else {
			$page = Session::get('');
		}
	
		$page = Session::get('proposerPublic.page', 0);
		$proposerId = Session::get('proposerPublic.proposerId', $datas['id']);
		$limit = Session::get('proposerPublic.limit', $datas['video_num']);
		$videoLinkPattern = Session::get('proposerPublic.videoLinkPattern', $datas['video_link_pattern']);
	}
	
	public static function getViewDatas($datas) {
		#var_dump("fut0");
		#die();
		$datas['view'] = 'helper.proposer.default';
		$datas['styleCss']["URL::asset('css/proposer.css')"] = URL::asset('css/proposer.css');
		$datas['jsLinks']["URL::asset('js/proposer.js')"] = URL::asset('js/proposer.js');
		
		$proposerName = isset($datas['proposer_name']) ? $datas['proposer_name'] : 'new';#
		$proposer = Proposer::where('name', '=', $proposerName)->first();#
		$proposerId = $proposer->proposer_type_id;#
		$videoNum = isset($datas['video_num']) ? $datas['video_num'] : 15;#
		$videoLinkPattern = isset($datas['video_link_pattern']) ? $datas['video_link_pattern'] : "/video/videoname/videoid";#
		$needMore = isset($datas['need_more']) ? $datas['need_more'] : 0;#
		$needPager = isset($datas['need_pager']) ? $datas['need_pager'] : 0;#
		$needMenu = isset($datas['need_menu']) ? $datas['need_menu'] : 0;#
		$moreLink = isset($datas['more_link']) ? $datas['more_link'] : ($needMore ? $proposerName : "");#
		$menuArray = isset($datas['menu_array']) ? $datas['menu_array'] : array();
		$needTitle = isset($datas['need_title']) ? $datas['need_title'] : 0;#
		$advPosPre = isset($datas['advPos']) ? explode(",", $datas['advPos']) : array(0,0);#
		$onlyFirst = isset($datas['onlyFirst']) ? $datas['onlyFirst'] : 0;
		$propClass = isset($datas['class']) ? $datas['class'] : "";
		#var_dump("fut1");
		$page = 1;
		if (isset($_GET['page']) && $needPager) {
			$page = $_GET['page'];
		}
		if ($needTitle == 0 || $needTitle > $page) {
			$needTitle = 0;
		} else {
			$needTitle = 1;
		}
                #echo 'propdump<br/>';
                if ($needMenu == 0 || $needMenu > $page) {
                    $needMenu = 0;
                } else {
                    $needMenu = 1;
                }
               
                
		#var_dump("fut2");
		if ($onlyFirst && isset($_GET['page']) && $_GET['page'] > 1) {
			return false;
		}
		
		if ($propClass == "featured") {
			if (Session::has('featured_'.$proposerName)) {
				Session::forget('featured_'.$proposerName);
			}
		}
		#var_dump("fut3");
		
		#var_dump("fut4");
		$idDatas = $proposer->getDatasFromProposerToPublicALT($videoNum, $page, $propClass, $needPager);
		$randNumBase = count($idDatas['videos']) < $videoNum ? count($idDatas['videos']) : $videoNum;
		#var_dump("fut5");
		if ($propClass == "featured") {
		
			$proposerId = $proposerId+1111;
			$featuredDatas = array();
			foreach($idDatas['videos'] as $idf) {
				$featuredDatas[] = $idf['video_id'];
			}
			Session::put("featured_".$proposerName, $featuredDatas);
		}
		
		
		$advPos = array();
		$advPos2 = array();
		$rawAdvPos = isset($datas['advPos']) ? $datas['advPos'] : array(0,0,0);
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
		
		$datas['helperDataJson'] = 'helperDataJson';
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = $proposer->title;#
		}
		
		
		
		$datas['helperData']['class'] = $propClass;
		$datas['helperData']['proposer_id'] = $proposerId;
		$datas['helperData']['videos_datas'] = $idDatas['videos'];
		/*var_dump($videoNum);
		var_dump($page);
		var_dump($idDatas['count']);
		var_dump($idDatas['videos']);
		die();*/
		$datas['helperData']['maxpage'] = ceil($idDatas['count']/$videoNum);
		$datas['helperData']['video_link_pattern'] = $videoLinkPattern;
		$datas['helperData']['adv_pos'] = $advPos;
		
		$datas['helperData']['video_num'] = count($idDatas['videos']) < $videoNum ? count($idDatas['videos']) : $videoNum;
		$datas['helperData']['adv_num'] = $advNum;
		
		$datas['helperData']['need_more'] = $needMore;
		$datas['helperData']['need_menu'] = $needMenu;
		$datas['helperData']['need_title'] = $needTitle;
		$datas['helperData']['need_pager'] = $needPager;
		$datas['helperData']['act_page'] = $page;
		
		$datas['helperData']['title'] = $proposer->title;
		
		$datas['helperData']['more_link'] = $moreLink;
		$datas['helperData']['pagerBase'] = "?";
		
		$menuData = array();
		foreach($menuArray as $link) {
			$menuData[] = Proposer::where('name', 'like', $link)->get(array('name', 'title'))->toArray();
		}
		$datas['helperData']['menu_array'] = $menuData;
		
		if($proposerName == 'new') {
			$out = '<meta name="description" content="Watch newest porn videos on LiveRuby." />
<meta name="keywords" content="porn, sex,free porn, ruby, porn videos, sex videos, free pussy, pussy, adult entertainment" />
###prewlink###
###nextlink###';
		} else if ($proposerName == 'foryou'){
			$out = '<meta name="description" content="Watch porn videos For You on LiveRuby." />
<meta name="keywords" content="porn, sex,free porn, ruby, porn videos, sex videos, free pussy, pussy, adult entertainment" />
###prewlink###
###nextlink###';
		} else if($proposerName == 'top') {
			$out = '<meta name="description" content="Watch top porn videos on LiveRuby." />
<meta name="keywords" content="porn, sex,free porn, ruby, porn videos, sex videos, free pussy, pussy, adult entertainment" />
###prewlink###
###nextlink###';
		}
 		
		$out = str_replace('###prewlink###', '', $out);
		$out = str_replace('###nextlink###', '', $out);
		if ($datas['metaDatas'] == '') {
			$datas['metaDatas'] = $out;
		}
		
		return $datas;
	}
	

	public static function getAdminDatas($datas) {
	
		$limit = 20;
		if (isset($_GET['limit'])) {
			$limit = $_GET['limit'];
		}
		$datas['helperData']['limit'] = $limit;
	
		$page = 1;
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		}
		$datas['helperData']['page'] = $page;
		
		
		$datas['view'] = 'helper.admin.'.'proposer'.'.list';
		$datas['styleCss'] = array();
		$datas['jsLinks'] = array();
		$datas['helperDataJson'] = 'helperDataJson';
		if (isset($datas['id'])) return self::subFunc($datas);
		$query = Proposer::take($limit);
		if ($page > 1) {
			$skip = ($page-1)*$limit;
			$query->skip($skip);
		}
		$datas['helperData']['proposers'] = $query->get();
		$count = Proposer::count();
		$datas['helperData']['needPager'] = $count/$limit > 1 ? 1 : 0;
		$datas['helperData']['pagerOptions'] = ceil($count/$limit);
		return $datas;
	}

}