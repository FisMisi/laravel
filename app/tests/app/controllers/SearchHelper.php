<?php

class SearchHelper extends BaseController {

	public function getstars($tx) {
		$query = Star::join('videos_stars', 'videos_stars.star_id', '=', 'stars.star_id');
	
		return $query->where("star_name", "ilike", "%".$tx."%")->groupBy('stars.star_id')->take(8)->get(array('stars.star_id as i', 'star_name as t'))->toArray();
	}

	public function gettag($tx) {
		return InternalTag::where('active', '=', 1)->where("internal_tag_name", "ilike", "%".$tx."%")->take(5)->orderBy('pos')->get(array('internal_tag_id as i', 'internal_tag_name as t'))->toArray();
	}

	public function getdatatosearch () {
		$data = Input::all();
		$tx = $data['tx'];
		$ret = array();
		$ret['stars'] = $this->getstars($tx);
		$ret['tags'] = $this->gettag($tx);
		return $ret;
	}
	
	
	
	private static function getStar($datas) {
		
	}
	
	private static function getFreetext($datas, $text) {
		
	}
	
	public static function getViewDatas($datas) {
		$outMeta = "";
		$videoLinkPattern = isset($datas['video_link_pattern']) ? $datas['video_link_pattern'] : "/video/videoname/videoid";#
		$advPosPre = isset($datas['advPos']) ? explode(",", $datas['advPos']) : array(0,0);#
		$propClass = isset($datas['class']) ? $datas['class'] : "";
		$videoNum = isset($datas['video_num']) ? $datas['video_num'] : 15;#
		$needMore = 0;#
		$needPager = isset($datas['need_pager']) ? $datas['need_pager'] : 0;#
		$needMenu = isset($datas['need_menu']) ? $datas['need_menu'] : 0;#
		$moreLink = "";
		$menuArray = isset($datas['menu_array']) ? $datas['menu_array'] : array();
		$needTitle = isset($datas['need_title']) ? $datas['need_title'] : 0;#
		$onlyFirst = isset($datas['onlyFirst']) ? $datas['onlyFirst'] : 0;
		$page = 1;
		if (isset($_GET['page']) && $needPager) {
			$page = $_GET['page'];
		}
		if ($needTitle == 0 || $needTitle > $page) {
			$needTitle = 0;
		} else {
			$needTitle = 1;
		}
		
		if ($needMenu == 0 || $needMenu > $page) {
			$needMenu = 0;
		} else {
			$needMenu = 1;
		}
		
		$order = "";
		if (isset($_GET['order'])) {
			$order = $_GET['order'];
		} else if (isset($datas['orderFromTop'])) {
			$order = $datas['orderFromTop'];
		}
		if ($onlyFirst && isset($_GET['page']) && $_GET['page'] > 1) {
			return false;
		}
		
		$actualRoute = Route::getCurrentRoute()->getPath();
		$actualRouteElements = explode('/', $actualRoute);
		
		#var_dump('fut');
		if (is_array($actualRouteElements) && (in_array('category', $actualRouteElements) || in_array('videoteszt', $actualRouteElements))) {#category
			$category = InternalTag::find($datas['category_id']);
			#var_dump($datas['category_id']);
			$title = $category->internal_tag_name;
			#var_dump($title);
			$baseMenuLink = "category/".$category->internal_tag_name."/".$category->internal_tag_id;
			if ($propClass == "featured") {
				if (Session::has('featuredcat_'.$datas['category_id'])) {
					Session::forget('featuredcat_'.$datas['category_id']);
				}
			}
			$idDatas = $category->getToSearch($videoNum, $page, $propClass, $order);
			$featuredDatas = array();
			foreach($idDatas['videos'] as $idf) {
				$featuredDatas[] = $idf['video_id'];
			}
			if ($propClass == "featured") {
				Session::put("featuredcat_".$datas['category_id'], $featuredDatas);
			}
			$proposerId = $datas['category_id'];
			
			if ($propClass == "featured") {
		
				$proposerId = $proposerId+1111;
				$featuredDatas = array();
				foreach($idDatas['videos'] as $idf) {
					$featuredDatas[] = $idf['video_id'];
				}
				Session::put('featuredcat_'.$datas['category_id'], $featuredDatas);
			}
			
			if ($datas['metaDatas'] == "") {
				$outDatas = '<meta name="description" content="Watch ###category### videos on LiveRuby." />
<meta name="keywords" content="###category###, LiveRuby, porn, free video, porn video,  adult entertainment" />
###prewlink###
###nextlink###';
				$outDatas = str_replace('###category###', $category->internal_tag_name, $outDatas);

				$outDatas = str_replace('###prewlink###', '', $outDatas);
				$outDatas = str_replace('###nextlink###', '', $outDatas);
				$datas['metaDatas'] = $outDatas;
			}
		
		} else if (is_array($actualRouteElements) && in_array('star', $actualRouteElements)) {#stars
			$starObj = Star::find($datas['star_id']);
			
			$title = $starObj->star_name;
			$baseMenuLink = "star/".$starObj->star_name."/".$starObj->star_id;
			
			if ($propClass == "featured") {
				if (Session::has('featuredstar_'.$datas['star_id'])) {
					Session::forget('featuredstar_'.$datas['star_id']);
				}
			}
			
			$idDatas = $starObj->getToSearch($videoNum, $page, $propClass, $order);
			$featuredDatas = array();
			foreach($idDatas['videos'] as $idf) {
				$featuredDatas[] = $idf['video_id'];
			}
			if ($propClass == "featured") {
				Session::put("featuredstar_".$datas['star_id'], $featuredDatas);
			}
			
			$proposerId = $datas['star_id'];
			
			if ($propClass == "featured") {
		
				$proposerId = $proposerId+1111;
				$featuredDatas = array();
				foreach($idDatas['videos'] as $idf) {
					$featuredDatas[] = $idf['video_id'];
				}
				Session::put('featuredcat_'.$datas['star_id'], $featuredDatas);
			}
			if ($datas['metaDatas'] == "") {
				$outDatas = '<meta name="description" content="Watch videos from ###star### on LiveRuby." />
<meta name="keywords" content="###star###, LiveRuby, porn, free video, porn video,  adult entertainment" />
###prewlink###
###nextlink###';
				$outDatas = str_replace('###star###', $starObj->star_name, $outDatas);

				$outDatas = str_replace('###prewlink###', '', $outDatas);
				$outDatas = str_replace('###nextlink###', '', $outDatas);
				$datas['metaDatas'] = $outDatas;
			}
		} else {#video from title
			$proposerId = 1000;
			$title = "Search";
			$baseMenuLink = "search/".$datas['text'];
			if ($propClass == "featured") {
				if (Session::has('featuredtext_'.$datas['text'])) {
					Session::forget('featuredtext_'.$datas['text']);
				}
			}
			$idDatas = Video::getToSearch($videoNum, $page, $propClass, $order, $datas['text']);
			
			if ($propClass == "featured") {
		
				$proposerId = $proposerId+1111;
				$featuredDatas = array();
				foreach($idDatas['videos'] as $idf) {
					$featuredDatas[] = $idf['video_id'];
				}
				Session::put('featuredtext_'.$datas['text'], $featuredDatas);
			}
			if ($datas['metaDatas'] == "") {
				$outDatas = '<meta name="description" content="Watch videos on LiveRuby." />
<meta name="keywords" content="###searchtext###, LiveRuby, porn, free video, porn video,  adult entertainment" />
###prewlink###
###nextlink###';
				$outDatas = str_replace('###searchtext###', $datas['text'], $outDatas);

				$outDatas = str_replace('###prewlink###', '', $outDatas);
				$outDatas = str_replace('###nextlink###', '', $outDatas);
				$datas['metaDatas'] = $outDatas;
			}
		}
		$randNumBase = count($idDatas['videos']) < $videoNum ? count($idDatas['videos']) : $videoNum;
		
		
		$advPos = array();
		$advPos2 = array();
		$rawAdvPos = isset($datas['advPos']) ? $datas['advPos'] : array(0,0);
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
				$advPos = array();
			}
		
		
			
		} 
		$advNum = count($advPos);#
		
		$datas['helperDataJson'] = 'helperDataJson';
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = $title;#
		}
		
		
		//-----------------------------------------------------------------------------------------;
		$datas['view'] = 'helper.proposer.default';
		$datas['styleCss']["URL::asset('css/proposer.css')"] = URL::asset('css/proposer.css');
		$datas['jsLinks']["URL::asset('js/proposer.js')"] = URL::asset('js/proposer.js');
		
		$datas['helperDataJson'] = 'helperDataJson';#done
		$datas['helperData']['class'] = $propClass;#done
		$datas['helperData']['proposer_id'] = $proposerId;#done
		$datas['helperData']['videos_datas'] = $idDatas['videos'];#done (C)
		
		$datas['helperData']['maxpage'] = ceil($idDatas['count']/$videoNum);#done (C)
		$datas['helperData']['video_link_pattern'] = $videoLinkPattern;#done
		$datas['helperData']['adv_pos'] = $advPos;#done
		
		$datas['helperData']['video_num'] = count($idDatas['videos']) < $videoNum ? count($idDatas['videos']) : $videoNum;#done
		$datas['helperData']['adv_num'] = $advNum;#done
		
		$datas['helperData']['need_more'] = $needMore;#done
		$datas['helperData']['need_menu'] = $needMenu;#done
		$datas['helperData']['need_title'] = $needTitle;#done
		$datas['helperData']['need_pager'] = $needPager;#done
		$datas['helperData']['act_page'] = $page;#done
		
		$datas['helperData']['title'] = $title;#done (C)
		
		$datas['helperData']['more_link'] = $moreLink;
		
		$menuData = array();
		
		$pagerBase = "/".$baseMenuLink;
		
		if ($order != "" && $order != 'new') {
			$pagerBase.= "?order=".$order."&";
		} else {
			$pagerBase.= "?";
		}
		
		
		foreach($menuArray as $link) {
			$idMenuData = Proposer::where('name', 'like', $link)->get(array('name', 'title'))->toArray();
			
			if ($idMenuData[0]['name'] == 'new') {
				$idMenuData[0]['name'] = $baseMenuLink;
			} else {
				$idMenuData[0]['name'] = $baseMenuLink."?order=".$idMenuData[0]['name'];
			}
			$menuData[] = $idMenuData;
		}
		$datas['helperData']['menu_array'] = $menuData;#done(C)
		$datas['helperData']['pagerBase'] = $pagerBase;
		
	
		return $datas;
	}



}