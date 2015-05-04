<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
class InternalTag extends Eloquent implements RemindableInterface {

	use RemindableTrait;

	protected $table = 'internal_tags';
	protected $primaryKey = 'internal_tag_id';
	
	public function getToSearch($limit, $page, $propClass, $order, $notin="") {
		
		if ($page > 0) {
			$page--;
		}
	
		
		
		$query = Video::join('see_videos', 'see_videos.video_id', '=', 'videos.video_id');
		$query2 = Video::join('see_videos', 'see_videos.video_id', '=', 'videos.video_id');
		$query->join("videos_i_tags", "videos_i_tags.video_id", "=", "videos.video_id");
		$query2->join("videos_i_tags", "videos_i_tags.video_id", "=", "videos.video_id");
		$query->where("videos.active", "=", 1);
		$query->where("videos.active2", "=", 1);
		#$query->where('videos.video_id', '<', 2142896);
		#$query2->where('videos.video_id', '<', 2142896);
		$query2->where("videos.active", "=", 1);
		$query2->where("videos.active2", "=", 1);
		$query->where("videos_i_tags.internal_tag_id", "=", $this->internal_tag_id);
		$query2->where("videos_i_tags.internal_tag_id", "=", $this->internal_tag_id);
		
		if (strlen($notin)) {
			$query->where("videos.video_id", "<>", $notin);
			$query2->where("videos.video_id", "<>", $notin);
		}
		
		if (Session::has("featuredcat_".$this->internal_tag_id) && is_array(Session::get("featuredcat_".$this->internal_tag_id)) && count(Session::get("featuredcat_".$this->internal_tag_id))) {#ha az adott proposerhez van featured csatolva
			$query->whereRaw("videos.video_id NOT IN (".implode(',',Session::get("featuredcat_".$this->internal_tag_id)).")");
			$query2->whereRaw("videos.video_id NOT IN (".implode(',',Session::get("featuredcat_".$this->internal_tag_id)).")");
		}
		
		if($propClass == "featured") {
			$query->where("videos.partner_id", '=', 1);
			$query->where("videos.rating", '>', 4.00);
			$query->where("see_videos.see_count", '>', 10000);
			$query->where("videos.length", '>', '00:02:00');
		}
		
		if (false) {
			$query->groupBy("see_videos.see_count");
			$query->groupBy("videos.video_id");
		}
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
		if($propClass != "featured") {
			if (!Cache::has('category_count__'.$this->internal_tag_id)) {
				$countObj = $query2->selectRaw('count(distinct(videos.video_id)) as c')->get();
				$queries = DB::getQueryLog();
				$last_query = end($queries);
				#var_dump($last_query);
				$count = $countObj[0]->c;
				$ret['count'] = $count;
				Cache::add('category_count__'.$this->internal_tag_id, $count, 2);
			} else {
				$count = Cache::get('category_count__'.$this->internal_tag_id);
				$ret['count'] = $count;
			}
		} else {
			$ret['count'] = $limit;
		}
		$needCache = true;
		
		if (!Cache::has('category_data__'.$this->internal_tag_id."__page__".$page."__limit__".$limit."__order__".$order."__".$propClass) || !$needCache) {
			$videos = $query->selectRaw($getArray)->get();
			$queries = DB::getQueryLog();
			$last_query = end($queries);
			#var_dump($last_query);
			$retVideos = Video::getDatasToPublicFromVideosALT($videos);
			if ($page < 10) {
				Cache::add('category_data__'.$this->internal_tag_id."__page__".$page."__limit__".$limit."__order__".$order."__".$propClass, $retVideos, 2);
			}
		} else {
			$retVideos = Cache::get('category_data__'.$this->internal_tag_id."__page__".$page."__limit__".$limit."__order__".$order."__".$propClass);
		}
		$ret['videos'] = $retVideos;
		if($propClass == "featured" && $limit > count($retVideos)) {
			$idDatas = $this->getToSearch($limit-count($retVideos), 1, "", $order, $notin="");
			$ret['videos'] = array_merge($idDatas['videos'], $retVideos);
			$ret['count'] = count($ret['videos']);
		}
		
		
		return $ret;
	}
	
	public static function getTagNameTagId() {
		if (!Cache::has('tags_tagname_tagid_all')) {
			$datas = self::where('active', '=', '1')->orderBy('internal_tag_name')->get(array('internal_tag_id', 'internal_tag_name'))->toArray();
			Cache::add('tags_tagname_tagid_all', $datas, 2);
			return $datas;
		}
		return Cache::get('tags_tagname_tagid_all');
	}
	
	public static function getTagNameTagIdToString($text) {
		$query = self::where('active', '=', '1');
		$query->where('internal_tag_name', 'like', '%'.$text,'%');
		$query->orderBy('internal_tag_name');
		return $query->get(array('internal_tag_id', 'internal_tag_name'))->toArray();
	}
	
	public static function getTagNameTagIdToStringFromFirstChar($text) {
		$query = self::where('active', '=', '1');
		$query->where('internal_tag_name', 'like', $text,'%');
		$query->orderBy('internal_tag_name');
		return $query->get(array('internal_tag_id', 'internal_tag_name'))->toArray();	
	}
	
	
	public static function getExternalsToInternalId($id) {
	
		$query = self::join('i_e_tags', 'i_e_tags.internal_tag_id', '=', 'internal_tags.internal_tag_id');
		$query->join('external_tags', 'external_tags.external_tag_id', '=', 'i_e_tags.external_tag_id');
		$query->join('partners', 'partners.partner_id', '=', 'external_tags.partner_id');
		$query->where('internal_tags.internal_tag_id', '=', $id);
		return $query->get(array('external_tags.external_tag_id', 'external_tag_name', 'partners.partner_id', 'partner_name', 'i_e_tags.i_e_tag_id'))->toArray();
	}
	
	
	public function updateVideosByITActive($old, $new, $externalTagId = 0) {
		if ($externalTagId == 0) {
		#ideiglenes!!!
		return true;
		
			$whereRaw1 = "video_id IN (SELECT video_id FROM videos_i_tags WHERE internal_tag_id = ?)";
			$whereId1 = $this->internal_tag_id;
		} else {
			$whereRaw1 = "video_id IN (SELECT video_id FROM videos_e_tags WHERE external_tag_id = ?)";
			$whereId1 = $externalTagId;
		}
	
		
		if ($old == $new) return true;
		if ($old == 0 && $new == 1) {
			Video::whereRaw($whereRaw1, array($whereId1))->where('active', '=', 0)->update(array('active' => 1));
		} else if ($old == 1 && $new == 0) {
			$query = Video::whereRaw($whereRaw1, array($whereId1))->where('active', '=', 1);
			$wr = "video_id NOT IN (SELECT video_id FROM videos_i_tags vit JOIN internal_tags i ON (vit.internal_tag_id = i.internal_tag_id) WHERE i.active = ?)";
			$query->whereRaw($wr, array(1))->update(array('active'=>0));
			
			
		} else if ($new == 2) {
			Video::whereRaw($whereRaw1, array($whereId1))->update(array('active' => 2));
		} else if ($old == 2 && $new == 1) {
			$whereRaw2 = "video_id NOT IN (SELECT vit.video_id FROM videos_i_tags vit JOIN internal_tags i ON (i.internal_tag_id = vit.internal_tag_id) WHERE i.active = ? )";
			Video::whereRaw($whereRaw1, array($whereId1))->whereRaw($whereRaw2, array(2))->update(array('active' =>1));
		} else {#$old == 2 && $new == 0
			$whereRaw3 = "video_id NOT IN (SELECT vit.video_id FROM videos_i_tags vit JOIN internal_tags i ON (i.internal_tag_id = vit.internal_tag_id) WHERE i.active = ? OR i.active = ?)";
			Video::whereRaw($whereRaw1, array($whereId1))->whereRaw($whereRaw3, array(2, 1))->update(array("active" => 0));
			$whereRaw4 = "video_id NOT IN (SELECT vit.video_id FROM videos_i_tags vit JOIN internal_tags i ON (i.internal_tag_id = vit.internal_tag_id) WHERE i.active = ? ) AND video_id IN (SELECT vit.video_id FROM videos_i_tags vit JOIN internal_tags i ON (i.internal_tag_id = vit.internal_tag_id) WHERE i.active = ? )";
			Video::whereRaw($whereRaw1, array($whereId1))->whereRaw($whereRaw4, array(2, 1))->update(array("active" => 1));
			
		}
		return true;
	}
	
	public function removeFromExternal($externalTagId) {
		$a = IETag::where('internal_tag_id', '=', $this->internal_tag_id)->where('external_tag_id', '=', $externalTagId)->delete();
		$this->removeFromVideos($externalTagId);
	}
	
	public function removeFromVideos($externalTagId) {
	$whereRaw = "video_id IN (SELECT video_id FROM videos_e_tags WHERE external_tag_id = ?)".
				" AND video_id NOT IN (SELECT video_id FROM videos_e_tags where external_tag_id <> ? ".
				" AND external_tag_id IN (SELECT external_tag_id FROM i_e_tags WHERE internal_tag_id = ?))";
		VideoITag::where('internal_tag_id', '=', $this->internal_tag_id)->whereRaw($whereRaw, array($externalTagId, $externalTagId, $this->internal_tag_id))->delete();
		$this->setVideosActiveAfterRemowe($externalTagId);
	}
	
	public function adsToExternalTag($externalTagId) {
		$whereRaw1 = "video_id IN (SELECT video_id FROM videos_e_tags WHERE external_tag_id = ?)";
		$ieTag = new IETag();
		$ieTag->internal_tag_id = $this->internal_tag_id;
		$ieTag->external_tag_id = $externalTagId;
		$ieTag->save();
		$insert = "INSERT INTO videos_i_tags (video_id, internal_tag_id, active, updated_at, created_at) (SELECT video_id, ".$this->internal_tag_id.",1, '".date("Y-m-d h:i:m")."', '".date("Y-m-d h:i:m")."' FROM videos_e_tags WHERE external_tag_id = ".$externalTagId." AND video_id NOT IN (SELECT video_id FROM videos_i_tags WHERE active=1 AND internal_tag_id = ".$this->internal_tag_id." ));";
		DB::unprepared($insert);
		if ($this->active != 0) {
			Video::whereRaw($whereRaw1, array($externalTagId))->where('active', '=', 0)->update(array('active' => $this->active));
		} else if ($this->active == 2) {
			Video::whereRaw($whereRaw1, array($externalTagId))->where('active', '=', 1)->update(array('active' => $this->active));
		}
		return true;
	}
	
	public function setVideosActiveAfterRemowe($externalTagId) {
		$this->updateVideosByITActive($this->active, 0, $externalTagId);
	}
}