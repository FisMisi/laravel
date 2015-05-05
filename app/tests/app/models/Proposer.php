<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Proposer extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'proposer_types';
	protected $primaryKey = 'proposer_type_id';

	public function getDatasFromProposerToPublicALT($limit, $page, $propClass, $needPager = 1) {
		
		if ($page > 0) {
			$page--;
		}
		
		#$query->join("internal_tags", "internal_tags.internal_tag_id", "=", "videos_i_tags.internal_tag_id");
		$query = Video::join('see_videos', 'see_videos.video_id', '=', 'videos.video_id');
		
		$hasCJoin = false;
		if($this->where_sql != "") {
			#ha nincs where akkor biztos nem kell a tag join
			$query->join("videos_i_tags", "videos_i_tags.video_id", "=", "videos.video_id");
			$query2Video::join("videos_i_tags", "videos_i_tags.video_id", "=", "videos.video_id");
			
			$query->whereRaw($this->where_sql);
			$query2->whereRaw($this->where_sql);
			$query2->where("videos.active", "=", 1);
			$query2->where("videos.active2", "=", 1);
		} else {
			$query2= Video::where("videos.active", "=", 1);
			$query2->where("videos.active2", "=", 1);
		} 
		$query->where("videos.active", "=", 1);
		$query->where("videos.active2", "=", 1);
		#$query->where('videos.video_id', '<', 2142896);
		#$query2->where('videos.video_id', '<', 2142896);
		if (Session::has("featured_".$this->name)) {#ha az adott proposerhez van featured csatolva
			$query->whereRaw("videos.video_id NOT IN (".implode(',',Session::get("featured_".$this->name)).")");
			$query2->whereRaw("videos.video_id NOT IN (".implode(',',Session::get("featured_".$this->name)).")");
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
		if($this->order_sql != "") {
			$query->orderByRaw($this->order_sql);
		}
		$query->take($limit);
		if ($page) {
			$query->skip($limit*$page);
		}
		$ret = array();
		$getArray = 'videos.video_id, videos.video_name, default_thumb, videos.video_seo_name, length, sum_rating, rating_number, videos.rating, see_videos.see_count';
		if($propClass != "featured" && $needPager) {
			if (!Cache::has('proposer_count__'.$this->proposer_type_id)) {
				$countObj = $query2->selectRaw('count(videos.video_id) as c')->get();
				$queries = DB::getQueryLog();
				$last_query = end($queries);
				#var_dump($last_query);
				$count = $countObj[0]->c;
				$ret['count'] = $count;
				Cache::add('proposer_count__'.$this->proposer_type_id, $count, 2);
			} else {
				$count = Cache::get('proposer_count__'.$this->proposer_type_id);
				$ret['count'] = $count;
			}
		} else {
			$ret['count'] = $limit;
		}
		
		
		/*$queries = DB::getQueryLog();
		$last_query = end($queries);
		var_dump($queries);die();*/
		#return $videos;
		
		if (!Cache::has('proposer_data__'.$this->proposer_type_id."__page__".$page."__limit__".$limit)) {
			$videos = $query->selectRaw($getArray)->get();
			$queries = DB::getQueryLog();
			$last_query = end($queries);
			#var_dump($last_query);
			$retVideos = Video::getDatasToPublicFromVideosALT($videos);
			if ($page < 10) {
				Cache::add('proposer_data__'.$this->proposer_type_id."__page__".$page."__limit__".$limit, $retVideos, 2);
			}
		} else {
			$retVideos = Cache::get('proposer_data__'.$this->proposer_type_id."__page__".$page."__limit__".$limit);
		}
		$ret['videos'] = $retVideos;
		
		if($propClass == "featured" && $limit > count($retVideos)) {
			$idArray = $this->getDatasFromProposerToPublicALT($limit-count($retVideos), 1, "");
			$ret['videos'] = array_merge($idArray['videos'], $retVideos);
			$ret['count'] = count($ret['videos']);
		}
		
		return $ret;
	}
	
	public function getDatasFromProposerToPublic($limit, $page) {
		$query = Video::join("videos_i_tags", "videos_i_tags.video_id", "=", "videos.video_id");
		$query->join("internal_tags", "internal_tags.internal_tag_id", "=", "videos_i_tags.internal_tag_id");
		$query->where("videos.active", "=", 1);
		$query->where("videos.active2", "=", 1);
		if($this->where_sql != "") $query->whereRaw($this->where_sql);
		$query->groupBy("videos.video_id");
		if($this->order_sql != "") $query->orderByRaw($this->order_sql);
		$query->take($limit);
		if ($page) {
			$query->skip($limit*$page);
		}
		
		$getArray = 'videos.video_id, videos.video_name, default_thumb, videos.video_seo_name, length, sum_rating, rating_number, array_agg(internal_tag_name) as tags';
		
		$videos = $query->selectRaw($getArray)->get();
		/*$queries = DB::getQueryLog();
		$last_query = end($queries);
		var_dump($queries);die();*/
		#return $videos;
		$queries = DB::getQueryLog();
		return Video::getDatasToPublicFromVideos($videos);
	}
	
}