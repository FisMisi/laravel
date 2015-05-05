<?php

/**
 * Videok megjelenitesehez szukseges adatok osszekesziteseert felelos osztaly
 *
 * @author: Paronai Tamás
 */
class VideoHelper extends BaseController {

	public static function getvidseo() {
		$data = Input::all();
		$videoId = $data['v'];
		$rawTitle = $data['t'];
		$title = base64_decode($rawTitle);
		$baseRet = Video::getSeoNameToTitle($title, "video", "see", $videoId);
		$ret = $baseRet['seoName'];
		return base64_encode($ret);
	}

	public static function downloadvideos() {
		$query = Video::join("see_videos", "see_videos.video_id", "=", "videos.video_id")->where('see_count_l', '>', 0);
		$query2 = Video::join("see_videos", "see_videos.video_id", "=", "videos.video_id")->where('see_count_l', '>', 0);
		$getArray = array("videos.video_id", "base_video_id", "length", "active", "active2", "created_at", "rating", "rating_l", "sum_rating", "sum_rating_l", "rating_number", "rating_number_l", "see_count", "see_count_l");
		
		$count = $query->count();
		$page = ceil($count/200);
		$basePath = storage_path();
		if (!file_exists($basePath.'/videoexport')) {
			mkdir($basePath.'/videoexport', 0770, true);
		}
		$path = $basePath.'/videoexport/';
		$file = "videos".date("Y_m_d_h_i_s").".csv";
		$del = ',';
		$newRow = "\n";	
		$exportData = '';
		$exportData.= 'video id'.$del.'base id'.$del.'video length'.$del.'active by tags'.$del.'active'.$del.'created'.$del.'rating all'.$del.'local rating'.$del.'sum rating all'.$del.'rating number all'.$del.'sum rating local'.$del.'rating number local'.$del.'see video all'.$del.'see video local'.$newRow;
		for($p = 0;$p < $page; $p++) {
			file_put_contents($path."rows.txt", $p);
			$idDatas = $query->take(200)->skip($p*200)->get($getArray)->toArray();
			foreach($idDatas as $row) {
				$exportData.= $row['video_id'].$del.$row['base_video_id'].$del.$row['length'].$del.$row['active'].$del.$row['active2'].$del.$row['created_at'].$del.$row['rating'].$del.$row['rating_l'].$del.$row['sum_rating'].$del.$row['rating_number'].$del.$row['sum_rating_l'].$del.$row['rating_number_l'].$del.$row['see_count'].$del.$row['see_count_l'].$newRow;
			}
			file_put_contents($path.$file, $exportData, FILE_APPEND | LOCK_EX);
			$exportData = '';
			
		}
		
		return Response::download($path.$file, $file);
	}

	/*
	 * video forras azonositojahoz legeneralja a src linket
	 */
	protected static function getRedtubeMP4LinkByVideoId($videoId) {
		$embedUrl = 'http://embed.redtube.com/?id='.$videoId;
		$source = file_get_contents($embedUrl);
		$source = substr($source, strpos($source, "source src=")+7);
		$source = substr($source, 0, strpos($source, " type="));
		return $source;
	}
	
	public function setRating() {
		$data = Input::all();
		$r = $data['r'];
		#var_dump($r);
		if($r == 0) {
			$rating = 0;
		} else {
			$rating = 5;
		}
		#var_dump($rating);
		$videoId = $data['v'];
		if (Ratings::canRating($videoId)) {
		Ratings::setRating($videoId, $rating);
		} else {
			return 'false';
		}
		
		$video = Video::where('video_id', '=', $videoId)->get(array('rating'))->toArray();
		$ret = array();
		$ret['rat'] = ceil($video[0]['rating']*20);
		$ret['urat'] = $rating;
		return $ret;
	}
	
	public static function regvidthumbs() {
		$data = Input::all();
		$v = $data['v'];
		if(!Auth::check()) {
			return 3;
		}
		
		if (!Auth::user()->admin) {
			return 4;
		}
		
		$video = Video::find($v);
		$apBase = "http://api.redtube.com/?data=redtube.Videos.getVideoById&video_id=".$video->base_video_id."&output=json&thumbsize=big";
		$json = file_get_contents($apBase);
		$datas = json_decode($json);
		if($datas->code == 2002) {
			$video->active2 = 0;
			$video->inactivation_time = date("Y-m-d H:i:s");
			$video->inactivation_user_id = Auth::user()->user_id;
			$video->inactivation_reason = "Remove from public";
			$video->save();
			return 2;
		}  else if ($datas->code == 1005){
			return 0;
		} else {
			$videoDatas = $datas->video->thumbs;
			$default = str_replace('m.jpg', 'b.jpg', $datas->video->default_thumb);
			$ret = array();
			foreach($videoDatas as $thumbs) {
				$ret[] = $thumbs->src;
			}
			$video->generateThumbsDirectory($ret);
			$video->default_thumb = $default;
			$video->save();
			return 1;	
		}
	}
	
	public static function savevideo() {
		$datas = Input::all();
		
		$video = Video::find($datas['video_id']);
		$video->video_name = $datas['video_name'];
		$video->video_seo_name = $datas['video_seo_name'];
		$returnString = makeSlugs($datas['video_name']);
		if(strlen($returnString)) {
			$video->valid_seo = 1;
		} else {
			$video->valid_seo = 0;
		}
		$video->active2 = $datas['active2'];
		$video->save();
		return Redirect::to("administrator/video/".$video->video_id);
	}
	
	protected static function subFunc($datas) {
		$datas['view'] = 'helper.admin.'.'video'.'.modify';
		$video = Video::find($datas['id']);
		$datas['helperData']['video'] = $video;
		$partner = Partner::find($video->partner_id);
		$datas['helperData']['partner'] = $partner->parner_name;
		$datas['helperData']['thumbs'] = $video->getAllThumbs();
		$datas['helperData']['tags'] = implode(";", $video->getAllTags());
		return $datas;
	}
	
	public static function getAdminDatas($datas) {
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['view'] = 'helper.admin.'.'video'.'.list';
		
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		#partner, internaltag, validseo, active(0: nem jelenik meg, 1: megjelenik, 2: tilt);
		
		if (isset($datas['id'])) return self::subFunc($datas);
		
		$partner = 0;
		if (isset($_GET['partner'])) {
			$partner = $_GET['partner'];
			$datas['helperData']['partner'] = $_GET['partner'];
		} else {
			$datas['helperData']['partner'] = 0;
		}
		$datas['helperData']['partnerList'] = Partner::get(array('partner_id', 'partner_name'))->toArray();
		
		$itID = 0;
		if (isset($_GET['itid'])) {
			$itID = $_GET['itid'];
			$datas['helperData']['itid'] = $_GET['itid'];
		} else {
			$datas['helperData']['itid'] = 0;
		}
	
		$datas['helperData']['internalList'] = InternalTag::get(array('internal_tag_id', 'internal_tag_name'))->toArray();
		
		$validseo = 2;
		if (isset($_GET['validseo'])) {
			$validseo = $_GET['validseo'];
			$datas['helperData']['validseo'] = $_GET['validseo'];
		} else {
			$datas['helperData']['validseo'] = 2;
		}
		
		$active = 3;
		if (isset($_GET['active'])) {
			$active = $_GET['active'];
			$datas['helperData']['active'] = $_GET['active'];
		} else {
			$datas['helperData']['active'] = 3;
		}
		
		$active2 = 2;
		if (isset($_GET['active2'])) {
			$active2 = $_GET['active2'];
			$datas['helperData']['active2'] = $_GET['active2'];
		} else {
			$datas['helperData']['active2'] = 2;
		}
		
		$limit = 20;
		if (isset($_GET['limit'])) {
			$limit = $_GET['limit'];
			$datas['helperData']['limit'] = $_GET['limit'];
		} else {
			$datas['helperData']['limit'] = 20;
		}
		
		$page = 1;
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
			$datas['helperData']['page'] = $_GET['page'];
		} else {
			$datas['helperData']['page'] = 1;
		}
		
		$idDatas = Video::getVideoToAdminList($partner, $itID, $validseo, $active, $active2, $limit, $page);
		$datas['helperData']['videos'] = $idDatas['videos'];
		$datas['helperData']['needPager'] = $idDatas['count']/$limit > 1 ? 1 : 0;
		$datas['helperData']['pagerOptions'] = ceil($idDatas['count']/$limit);
		$datas['helperData']['videosCount'] = $idDatas['count'];
		
		return $datas;
	}

	/*
	 * Ez az ami osszeszedi az adatokat
	 */
	public static function getViewDatas($datas) {
	
		#beallitja a helper view-t ami a content megjeleniteset vegzi
		$datas['view'] = 'helper.video.default';
		$video = Video::find($datas['videoId']);
		$vs = VideoSee::where('video_id', '=', $video->video_id)->first();
		$vs->see_count = $vs->see_count+1;
		$vs->see_count_l = $vs->see_count_l+1;
		$vs->timestamps = false;
		$vs->save();
		#var_dump($vs->see_count);
		$datas['helperData']['iframe'] = $video->video_flash_link;
		$datas['helperData']['length'] = $video->length;
		$datas['helperData']['title'] = $video->video_name;
		$datas['helperData']['rating'] = ceil($video->rating*20);
		$datas['helperData']['video_id'] = $video->video_id;
		$isUserAdmin = 0;
		if (Auth::check()) {
			$ratingDatas = Ratings::whereRaw("(session_id = ? OR user_id = ?) AND video_id = ?", array(Session::getId(), Auth::user()->user_id,$video->video_id))->get(array('ratings'))->toArray();
			$pv = PrivateVideo::where('user_id', '=', Auth::user()->user_id)->where('video_id', '=', $datas['videoId'])->get(array('video_id'))->toArray();
			if(is_null($pv) || $pv === false || !is_array($pv) ||count($pv) == 0 ) {
				$datas['helperData']['canpv'] = 1;#hozza tudja adni
			} else {
				$datas['helperData']['canpv'] = 0;#ki tudja venni
			}
			
			if (Auth::user()->admin) {
				$isUserAdmin = 1;
			}
			
		} else {
			$ratingDatas = Ratings::where("session_id", "=", Session::getId())->where('video_id', '=', $video->video_id)->get(array('ratings'))->toArray();
			$datas['helperData']['canpv'] = 2;#meg se jelenik neki(esetleg később regre ösztönző szöveg)
		}
		
		$datas['helperData']['isUserAdmin'] = $isUserAdmin;
		
		#var_dump($ratingDatas);
		if (!is_null($ratingDatas) && $ratingDatas != array()) {
			$datas['helperData']['hasrating'] = 1;
			$datas['helperData']['userrating'] = $ratingDatas[0]['ratings'];
		} else {
			$datas['helperData']['hasrating'] = 0;
			$datas['helperData']['userrating'] = -1;
		} 
		#var_dump($datas['helperData']['hasrating']);
		#var_dump($datas['helperData']['userrating']);
		$q1 = InternalTag::join('videos_i_tags', 'videos_i_tags.internal_tag_id', '=', 'internal_tags.internal_tag_id');
		$tags = $q1->where('video_id', '=', $video->video_id)->get(array('internal_tags.internal_tag_id', 'internal_tag_name'))->toArray();
		$mvTagId = 0;
		$mvTagSeeNum = -1;
		$datas['helperData']['videotags'] = $tags;
		$tagListArray = array();
		foreach($tags as $t) {
			$tagListArray[] = $t['internal_tag_name'];
			$idTag = InternalTag::find($t['internal_tag_id']);
			$idTag->see_count = $idTag->see_count+1;
			if ($idTag->see_count > $mvTagSeeNum) {
				$mvTagSeeNum = $idTag->see_count;
				$mvTagId = $t['internal_tag_id'];
				$mvTagName = $t['internal_tag_name'];
			}
			$idTag->save();
		}
		if ($mvTagId == 0) {
			$mvTagId = 1;
			$mvTagName = 'amateur';
		}
		$datas['helperData']['tags'] = $tags;
		#ha nincs meg data, akkor beallitja
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = $video->video_name;
		}
		
		if ($datas['metaDatas'] == "") {
			#ekkor legeneraljuk
			$metaSource = '<meta name="description" content="###video_title### ###category### video from LiveRuby." />
<meta name="keywords" content="###category_list###, porn, free porn, porn videos, sex videos, sex, LiveRuby" />
<meta property="og:title" content="###video_title###" />
<meta property="og:length" content="###length###" />
<meta property="og:categories" content="###category_list###" />';
			
			$out = $metaSource;
			
			$out = str_replace("###video_title###", $video->video_name, $out);
			$out = str_replace("###category###", $mvTagName, $out);
			$out = str_replace("###category_list###", implode(",", $tagListArray), $out);
			$out = str_replace("###length###", $video->length, $out);
			$datas['metaDatas'] = $out;
		}
		
		$datas['styleCss']["URL::asset('css/video.css')"] = URL::asset('css/video.css');
		$datas['jsLinks']["URL::asset('js/video.js')"] = URL::asset('js/video.js');
		
		$partner = Partner::find($video->partner_id);
		switch ($partner->partner_name) {
			case 'Redtube':
				$datas['jsLinks']["URL::asset('js/video3.js')"] = URL::asset('js/video3.js');
			break;
			case 'Pornhub':
				$datas['jsLinks']["URL::asset('js/video2.js')"] = URL::asset('js/video2.js');
			break;
			case 'Youporn':
				$datas['jsLinks']["URL::asset('js/video1.js')"] = URL::asset('js/video1.js');
			break;
		}
		$datas['styleCss']["URL::asset('css/proposer.css')"] = URL::asset('css/proposer.css');
		$datas['jsLinks']["URL::asset('js/proposer.js')"] = URL::asset('js/proposer.js');
		$datas['helperDataJson'] = 'helperDataJson';
		$videoNum = 4;
		$category = InternalTag::find($mvTagId);
		$datas['toOtherContent']['text'] = $category->internal_tag_seo_name;
		$datas['toOtherContent']['category_id'] = $category->internal_tag_id;
		$datas['toOtherContent']['orderFromTop'] = "top";
		$idDatas = $category->getToSearch($videoNum, 1, "featured", "top", $video->video_id);
		$datas['helperData']['video_num'] = count($idDatas['videos']) < $videoNum ? count($idDatas['videos']) : $videoNum;
		$datas['helperData']['video_link_pattern'] = "/video/videoname/videoid";
		$datas['helperData']['videos_datas'] = $idDatas['videos'];
		
		#felesleges adatok torlese
		#unset($datas['videoId']);
		
		#visszaadjaa kert adatokat
		return $datas;
	}
}