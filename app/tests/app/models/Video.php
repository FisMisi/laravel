<?php
require_once app_path()."/controllers/interfaces/SeoUrlSlug.php";

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Video extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'videos';
	protected $primaryKey = 'video_id';
	
	
	public static function getToSearch($limit, $page, $propClass, $order, $text) {
		if ($page > 0) {
			$page--;
		}
	
		
		
		$query = Video::join('see_videos', 'see_videos.video_id', '=', 'videos.video_id');
		$query2 = Video::join('see_videos', 'see_videos.video_id', '=', 'videos.video_id');
		$query->where("videos.active", "=", 1);
		$query->where("videos.active2", "=", 1);
		#$query->where('videos.video_id', '<', 2142896);
		$query2->where("videos.active", "=", 1);
		$query2->where("videos.active2", "=", 1);
		#$query2->where('videos.video_id', '<', 2142896);
		$query->where("videos.video_name", "ilike", "%".$text."%");
		$query2->where("videos.video_name", "ilike", "%".$text."%");
		
		if (Session::has("featuredtext_".$text)) {#ha az adott proposerhez van featured csatolva
			
			if(is_array(Session::get("featuredtext_".$text)) && count(Session::get("featuredtext_".$text))) {
		
				$query->whereRaw("videos.video_id NOT IN (".implode(',',Session::get("featuredtext_".$text)).")");
				$query2->whereRaw("videos.video_id NOT IN (".implode(',',Session::get("featuredtext_".$text)).")");
			}
		}
		
		if($propClass == "featured") {
			$query->where("videos.partner_id", '=', 1);
			$query->where("videos.rating", '>', 4.00);
			$query->where("see_videos.see_count", '>', 10000);
			$query->where("videos.length", '>', '00:02:00');
		}
		
		$query->groupBy("see_videos.see_count");
		$query->groupBy("videos.video_id");
		
		if (!in_array($order, array('top', 'new', 'foryou'))) {
			$order = 'new';
		}
		switch ($order) {
		
			case 'top':
				$query->orderByRaw('rating desc');
			break;
			case 'new':
				$query->orderByRaw('videos.video_id desc');
			break;
			case 'foryou':
				$query->orderByRaw('see_videos.see_count desc');
			break;
			default:
				$query->orderByRaw('videos.video_id desc');
			break;
		}
		$query->take($limit);
		if ($page) {
			$query->skip($limit*$page);
		}
		$ret = array();
		
		
		$getArray = 'videos.video_id, videos.video_name, default_thumb, videos.video_seo_name, length, sum_rating, rating_number, videos.rating, see_videos.see_count';
		$countObj = $query2->selectRaw('count(distinct(videos.video_id)) as c')->get();
		$count = $countObj[0]->c;
		$ret['count'] = $count;
		
		$videos = $query->selectRaw($getArray)->get();
		$retVideos = Video::getDatasToPublicFromVideosALT($videos);
		$ret['videos'] = $retVideos;
		
		return $ret;
	}
	
	public static function getDataToSearchList($text = "", $tag = null, $star = null, $limit = 15, $page = 1) {
		if ($page > 0) {
			$page--;
		}
		
		$query = self::where('videos.active', '=', 1);
		$query->where('videos.active2', '=', 1);
		#$query->where('videos.video_id', '<', 2142896);
		$query2 = self::where('videos.active', '=', 1);
		$query2->where('videos.active2', '=', 1);
		$query2->where('videos.active', '=', 1);
		#$query2->where('videos.video_id', '<', 2142896);
		
		if (!is_null($tag)) {
			$query->join('videos_i_tags', 'videos_i_tags.video_id', '=', 'videos.video_id');
			$query2->join('videos_i_tags', 'videos_i_tags.video_id', '=', 'videos.video_id');
		}
		
		if(!is_null($star)) {
			$query->join('videos_stars', 'videos_stars.video_id', '=', 'videos.video_id');
			$query2->join('videos_stars', 'videos_stars.video_id', '=', 'videos.video_id');
		}
		
		if ($text != "") {
			$query->where("videos.video_name", 'like', '%'.$text.'%');
			$query2->where("videos.video_name", 'like', '%'.$text.'%');
		}
		
		if (!is_null($tag)) {
			$query->where('videos_i_tags.internal_tag_id', '=', $tag);
			$query2->where('videos_i_tags.internal_tag_id', '=', $tag);
		}
		
		if (!is_null($star)) {
			$query->where('videos_stars.star_id', '=', $star);
			$query2->where('videos_stars.star_id', '=', $star);
		}
		$query->groupBy("videos.video_id");
		
		$query->take($limit);
		if ($page) {
			$query->skip($limit*$page);
		}
		$ret = array();
		
		$countObj = $query2->selectRaw('count(distinct(videos.video_id)) as c')->get();
		$count = $countObj[0]->c;
		$ret['count'] = $count;
		
		$getArray = 'videos.video_id, videos.video_name, default_thumb, videos.video_seo_name, length, sum_rating, rating_number';
		$videos = $query->selectRaw($getArray)->get();
		$retVideos = Video::getDatasToPublicFromVideosALT($videos);
		
		$ret['videos'] = $retVideos;
		
		return $ret;
	}
	
	public static function getVideoToAdminList($partnerId = 0, $itID = 0, $validSeo = 2, $active = 3, $active2 = 2, $limit = 20, $page = 1) {
		
		if ($page != 0) {
			$page--;
		}
		
		$query = self::join('partners', 'partners.partner_id', '=', 'videos.partner_id');
		$query2 = self::join('partners', 'partners.partner_id', '=', 'videos.partner_id');
		if($itID != 0) {
			$query->join('videos_i_tags', 'videos_i_tags.video_id', '=', 'videos.video_id');
			$query2->join('videos_i_tags', 'videos_i_tags.video_id', '=', 'videos.video_id');
			#$query->join('internal_tags', 'internal_tags.internal_tag_id', '=', 'videos_i_tags.internal_tag_id');
			$query->where('videos_i_tags.internal_tag_id', '=', $itID);
			$query2->where('videos_i_tags.internal_tag_id', '=', $itID);
		}
		if ($partnerId != 0) {
			$query->where('videos.partner_id', '=', $partnerId);
			$query2->where('videos.partner_id', '=', $partnerId);
		}
		
		if ($validSeo != 2) {
			$query->where('videos.valid_seo', '=', $validSeo);
			$query2->where('videos.valid_seo', '=', $validSeo);
		}
		
		if ($active != 3) {
			$query->where('videos.active', '=', $active);
			$query2->where('videos.active', '=', $active);
		}
		
		if ($active2 != 2) {
			$query->where('videos.active2', '=', $active2);
			$query2->where('videos.active2', '=', $active2);
		}
		
		$query->take($limit);
		if ($page > 0) {
			$query->skip($limit*$page);
		}
		
		$getArray = array('videos.video_id', 'video_name', 'active', 'active2', 'valid_seo', 'videos.partner_id', 'partner_name');
		$ret = array();
		$ret['videos'] = $query->get($getArray)->toArray();
		$ret['count'] = $query2->count();
		return $ret;
	
	}
	
	public static function setVideoDataFromRegenerate($datas) {
		$videoId = $datas->video_id;
		$videoDefaultThumb = $datas->default_thumb;
		$videoThumbs = $datas->thumbs;
		$video = self::find($videoId);
		$video->default_thumb = $videoDefaultThumb;
		$video->generateThumbsDirectory($videoThumbs);
	}
	
	public static function getVideosToRegenerate() {
		$query = self::where('active', '=', 1);
		#$query->where('active2', '=', 1);
		$query->where('default_thumb', '=', '');
		$query->where('partner_id', '=', 1);
		#$query->where('video_id', '>', 685578);
		$query->orderBy('video_id');
		$query->take(10000);
		$query->skip(20000);
		return $query->get(array('video_id', 'base_video_id'))->toArray();
	}
	
	public static function isActiveByBaseVideoId($baseVideoId, $partnerId) {
		$active = self::where('base_video_id', '=', $baseVideoId)->where('partner_id', '=', $partnerId)->get(array('active2'))->toArray();
		$active = reset($active);
		if ($active === false) {
			return null;
		} else {
			$active = reset($active);
		}
		return $active;
	}
	
	public static function hasBaseVideoId($baseVideoId, $tt) {
		$active = self::isActiveByBaseVideoId($baseVideoId, $tt);
		return !is_null($active);
	}
	
	public static function setInactiveVideoByBaseVideoId($baseVideoId, $removeUserId, $reason, $partnerId) {
		$video = self::where('partner_id', '=', $partnerId)->where('base_video_id', "=", $base_video_id)->first();
		if (!is_null($video)) {
			$video->active2 = 0;
			$video->inactivation_time = date("Y-m-d h:i:s");
			$video->inactivation_user_id = $removeUserId;
			$video->inactivation_reason = $reason;
			$video->save();
		}
	}
	
	public static function getSeoNameToTitle($title, $tag, $pre, $baseId) {
		$returnString = makeSlugs($title);
		if (strlen($returnString)) {
			return array('seoName' => $returnString, 'validSeo' => 1);
		}
		$returnString = makeSlugs(ExternalTag::reformatTag($tag));
		if(strlen($returnString)) {
			return array('seoName' => $returnString.$pre."-".$baseId, 'validSeo' => 0);
		}
		return array('seoName' => "video".$pre."-".$baseId, 'validSeo' => 0);
	}
	
	public static function getFlashLinkToToBaseVideoIdRt($videoId) {
		return "<iframe src=\"http://embed.redtube.com/?id=".$videoId."&bgcolor=000000\" frameborder=\"0\" width=\"434\" height=\"344\" scrolling=\"no\"></iframe>";
	}
	
	protected function setTag($tagName, $partnerId) {
		#$tagName = ExternalTag::reformatTag($tagName);
		$tagId = ExternalTag::where('external_tag_name', "=", $tagName)->where('partner_id', '=', $partnerId)->get(array("external_tag_id"))->toArray();
		if ($tagId === false || !is_array($tagId)) {#hibakezelés
			return true;
		}
		$tagId = reset($tagId);
		if ($tagId === false || !is_array($tagId)) {#hibakezelés
			return true;
		}
		$tagId = reset($tagId);
		if (!is_int($tagId)){
			return true;
		}
		$id = new VideoETag();
		$id->video_id = $this->video_id;
		$id->external_tag_id = $tagId;
		$id->save();
		$query = ExternalTag::join('i_e_tags', 'i_e_tags.external_tag_id', '=', 'external_tags.external_tag_id');
		$query->join('internal_tags', 'internal_tags.internal_tag_id', '=', 'i_e_tags.internal_tag_id');
		$whereRaw = "internal_tags.internal_tag_id NOT IN (SELECT internal_tag_id FROM videos_i_tags WHERE video_id = ?)";
		$query->whereRaw($whereRaw, array($this->video_id));
		$itArray = $query->where('external_tags.external_tag_id', '=', $tagId)->get(array('internal_tags.internal_tag_id', 'internal_tags.active'))->toArray();
		if (!is_null($itArray) && $itArray !== false) {
			foreach($itArray as $it) {#@todo: no ez itt fos! kulon kell csatolni internal, illetve external tag-eket videokhoz
				$vitObj = new VideoITag();
				$vitObj->video_id = $this->video_id;
				$vitObj->internal_tag_id = $it['internal_tag_id'];
				$vitObj->save();
				if ($it['active'] == 1 && $this->active != 2) $this->active = 1; 
				if ($it['active'] == 2) $this->active = 2;
			}
		}
		return true;
	}
	
	public function setTagsFromArray($tags, $partnerId) {
		$this->active = 0;
		foreach($tags as $tagName) {
			$tagName = ExternalTag::reformatTag($tagName);
			if ($tagName != "") {#hibakezelés
				$done = $this->setTag($tagName, $partnerId);
			}
		}
		$this->save();
	}
	
	public function setTags($tags, $partnerId = 1) {
		$this->active = 0;
		foreach($tags as $tag) {
			$tagName = $tag->tag_name;
			$tagName = ExternalTag::reformatTag($tagName);
			$this->setTag($tagName, $partnerId);
		}
		$this->save();
	}
	
	public function getAllTags() {
		$query = InternalTag::join('videos_i_tags', 'videos_i_tags.internal_tag_id', '=', 'internal_tags.internal_tag_id');
		$query->where('internal_tags.active', '=', '1');
		$tags = $query->where('video_id', '=', $this->video_id)->get(array('internal_tag_name', 'internal_tags.internal_tag_id'))->toArray();
		$ret = array();
		foreach($tags as $tag) {
			$ret[$tag['internal_tag_id']] = $tag['internal_tag_name'];
		}
		return $ret;
	}
	
	public function getAllThumbs() {
		$base = str_pad((string)$this->video_id, 9, '0', STR_PAD_LEFT);
		$b1 = substr($base, 0, 3);
		$b2 = substr($base, 3, 3);
		$b3 = substr($base, 6, 3);
		$basePath = storage_path();
		$ret = "";
		if (!file_exists($basePath.'/thumbcache/'.$b1."/".$b2."/".$b3."/".$base.".txt")) {
			return array();#Ha nem olvashato fel a file, akkor nem lapozodik, de legalabb nem dob hibat
		}
		$fh = fopen($basePath.'/thumbcache/'.$b1."/".$b2."/".$b3."/".$base.".txt", 'r');
		while (!feof($fh)) {
			$line = fgets($fh);
			if ($line != false) $ret = $line;
		}
		return explode(";", $ret);
	}
	
	public static function getDatasToPublicFromVideosALT($videos) {
		$returnDatas = array();
		$actVideos = array();
		$actId = 0;
		foreach($videos as $video) {
			if(in_array($video->video_id, $actVideos)) {
				
			} else {
				$id = array();
				$actVideos[] = $video->video_id;
				$id['video_id'] = $video->video_id;
				$id['default_thumb'] = $video->default_thumb;
				$id['thumbs'] = $video->getAllThumbs();
				$id['thumbsCount'] = count($id['thumbs']);
				$id['tags'] = $video->getAllTags();
				$id['video_name'] = $video->video_name;
				$id['video_seo_name'] = $video->video_seo_name;
				$id['rating'] = $video->rating;
				$id['see_count'] = $video->see_count;
				#$id['tags'] = $video->tags;
				$id['length'] = $video->length;
				
				$returnDatas[] = $id;
			}
		}
		return $returnDatas;
	}
	
	public static function getDatasToPublicFromVideos($videos) {
		$returnDatas = array();
		foreach($videos as $video) {
			$id = array();
			$id['video_id'] = $video->video_id;
			$id['default_thumb'] = $video->default_thumb;
			$id['thumbs'] = $video->getAllThumbs();
			$id['thumbsCount'] = count($id['thumbs']);
			$id['video_name'] = $video->video_name;
			$id['video_seo_name'] = $video->video_seo_name;
			$sum = $video->sum_rating;
			$num = $video->rating_number;
			if ($num != 0) {
				$rat = (double)$sum/$num;
			} else {
				$rat = 0;
			}
			
			$id['rating'] = round($rat, 2);
			$id['tags'] = $video->tags;
			$id['length'] = $video->length;
			
			$returnDatas[] = $id;
		}
		return $returnDatas;
	}
        
        
        
	
	#new to teszt
	public static function isValidFromRedtube($baseId, $lastId) {
		$apBase = "http://api.redtube.com/?data=redtube.Videos.getVideoById&video_id=".$baseId."&output=json&thumbsize=big";
		$json = file_get_contents($apBase);
		$datas = json_decode($json);
		if($datas->code == 2002) {
			return 2;
		} else if ($datas->code == 1005){
			var_dump($datas);
			var_dump($lastId);
			die();
			return 0;
		} else {
			$thumbs = json_encode($datas->video->thumbs);
			$idThumb = json_decode($thumbs);
			#var_dump($idThumb);
			#var_dump($idThumb[0]);
			#var_dump($idThumb[0]->src);
			$retThumbs = array();
			foreach($idThumb as $t) {
				$retThumbs[] = $t->src;
				#var_dump($t->src);
				#echo "<br>******************************<br>";
			}
			#var_dump($retThumbs);
			#echo "************************************************";
			$defaultThumb = str_replace('m.jpg', 'b.jpg', $datas->video->default_thumb);
		
			return array('thumbs' => $retThumbs, 'defaultThumbs' => $defaultThumb);
		}
	}
	
	public static function getValidCount($partnerId) {
		$query = self::where('partner_id', '=', $partnerId)->where('active', '=', 1)->where('active2', '=', 1);
	
		$countObj = $query->selectRaw('count(distinct(videos.video_id)) as c')->get();
		$count = $countObj[0]->c;
		return $count;
	}
	
	public static function getValidBaseIdsVideoIds($partnerId, $pageLimit, $offset) {
		$query = self::where("partner_id", "=", $partnerId);
		$query->where('active', '=', 1);
		$query->where('active2', '=', 1);
		$query->skip($offset);
		$query->take($pageLimit);
		$query->orderBy("video_id", "DESC");
		return $query->get(array('video_id', 'base_video_id'))->toArray();
	}
	
	public static function getThumbsToBaseId($baseId) {
		$apBase2 = "http://api.redtube.com/?data=redtube.Videos.getVideoById&video_id=".$baseId."&output=json&thumbsize=big";
		$json = file_get_contents($apBase2);
		$datas = json_decode($json);
		$videoDatas = $datas->video->thumbs;
		$default = str_replace('m.jpg', 'b.jpg', $datas->video->default_thumb);
		$ret = array();
		foreach($videoDatas as $thumbs) {
			$ret[] = $thumbs->src;
		}
		return array('default' => $default, 'array' => $ret);
	}
	
	public static function regenerateThumbs() {
		$pageLimit = 1000;
		$partnerId = 1;
		$regenerate = 0;
		$invalid = 0;
		$lastId = -1;
		$count = self::getValidCount($partnerId);
		$maxPage = ceil($count/1000);
		for($i = 0;$i<$maxPage;$i++) {
			$baseIds = self::getValidBaseIdsVideoIds($partnerId, $pageLimit, $i*$pageLimit);
			foreach($baseIds as $baseId) {
				$isValid = self::isValidFromRedtube($baseId['base_video_id'], $lastId);
				if($isValid == 1) {
					$thumbArray = self::getThumbsToBaseId($baseId['base_video_id']);
					$video = self::find($baseId['video_id']);
					$video->generateThumbsDirectory($thumbArray['array']);
					$video->default_thumb = $thumbArray['default'];
					$video->save();
					$regenerate++;
					$lastId = $baseId['video_id'];
				} else if($isValid == 2){
					$video = self::find($baseId['video_id']);
					$video->active2 = 0;
					$video->inactivation_user_id = 0;
					$video->inactivation_reason = "remove from src";
					$video->save();
					$invalid++;
					$lastId = $baseId['video_id'];
				}
			}
		}
		var_dump($regenerate);
		var_dump($invalid);
		var_dump($lastId);

	}
	
	public function generateThumbsDirectory($thumbs) {
	#global $paths;
		$base = str_pad((string)$this->video_id, 9, '0', STR_PAD_LEFT);
		$b1 = substr($base, 0, 3);
		$b2 = substr($base, 3, 3);
		$b3 = substr($base, 6, 3);
		$basePath = storage_path();
		if (!file_exists($basePath.'/thumbcache')) {
			mkdir($basePath.'/thumbcache', 0770, true);
		}
		
		if (!file_exists($basePath.'/thumbcache/'.$b1)) {
			mkdir($basePath.'/thumbcache/'.$b1, 0770, true);
		}
		
		if (!file_exists($basePath.'/thumbcache/'.$b1."/".$b2)) {
			mkdir($basePath.'/thumbcache/'.$b1."/".$b2, 0770, true);
		}
		
		if (!file_exists($basePath.'/thumbcache/'.$b1."/".$b2."/".$b3)) {
			mkdir($basePath.'/thumbcache/'.$b1."/".$b2."/".$b3, 0770, true);
		}
		#@todo ennek a formatuma meg valtozhat
		$fh = fopen($basePath.'/thumbcache/'.$b1."/".$b2."/".$b3."/".$base.".txt", 'w');
		$writeRet = fwrite($fh, implode(';', $thumbs));
		fclose($fh);
		if ($writeRet === false) {
			#ekkor kell egy import error log db insert
			#video_id, video_base_id, partner_id, timestamp 
			$ie = new ImportError();
			$ie->video_id = $this->video_id;
			$ie->base_video_id = $this->base_video_id;
			$ie->partner_id = $this->partner_id;
			$ie->save();
		}
		
	}
	
	public function generateThumbsDirectoryRT($thumbs) {
		$t = array();
		foreach($thumbs as $thumb) {
			$t[] = $thumb->src;
		}
		$this->generateThumbsDirectory($t);
	}
}