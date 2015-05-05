<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Star extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'stars';
	protected $primaryKey = 'star_id';
	
	public function getToSearch($limit, $page, $propClass, $order) {
		if ($page > 0) {
			$page--;
		}
	
		
		$query = Video::join('see_videos', 'see_videos.video_id', '=', 'videos.video_id');
		$query2 = Video::join('see_videos', 'see_videos.video_id', '=', 'videos.video_id');
		$query->join("videos_stars", "videos_stars.video_id", "=", "videos.video_id");
		$query2->join("videos_stars", "videos_stars.video_id", "=", "videos.video_id");
		$query->where("videos.active", "=", 1);
		$query->where("videos.active2", "=", 1);
		$query->where('videos.video_id', '<', 705065);
		$query2->where("videos.active", "=", 1);
		$query2->where("videos.active2", "=", 1);
		$query->where("videos_stars.star_id", "=", $this->star_id);
		$query2->where("videos_stars.star_id", "=", $this->star_id);
		
		if (Session::has("featuredstar_".$this->star_id) && is_array(Session::get("featuredstar_".$this->star_id)) && Session::get("featuredstar_".$this->star_id) != array()) {#ha az adott proposerhez van featured csatolva
			#var_dump(Session::has("featuredstar_".$this->star_id));
			#die();
			$query->whereRaw("videos.video_id NOT IN (".implode(',',Session::get("featuredstar_".$this->star_id)).")");
			$query2->whereRaw("videos.video_id NOT IN (".implode(',',Session::get("featuredstar_".$this->star_id)).")");
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
		
		if($propClass != "featured") {
		
			if (!Cache::has('star_count__'.$this->star_id)) {
				$countObj = $query2->selectRaw('count(distinct(videos.video_id)) as c')->get();
				$count = $countObj[0]->c;
				$ret['count'] = $count;
				Cache::add('star_count__'.$this->star_id, $count, 2);
			} else {
				$count = Cache::get('star_count__'.$this->star_id);
				$ret['count'] = $count;
			}
		} else {
			$ret['count'] = $limit;
		}
		$needCache = true;
		
		if (!Cache::has('star_data__'.$this->star_id."__page__".$page."__limit__".$limit."__order__".$order."__".$propClass) || !$needCache) {
			$videos = $query->selectRaw($getArray)->get();
			$queries = DB::getQueryLog();
			$last_query = end($queries);
			#echo "<br/><br/><br/>";
			#var_dump($last_query);
			#echo "<br/><br/><br/>";
			$retVideos = Video::getDatasToPublicFromVideosALT($videos);
			if ($page < 10) {
				Cache::add('star_data__'.$this->star_id."__page__".$page."__limit__".$limit."__order__".$order."__".$propClass, $retVideos, 2);
			}
		} else {
			$retVideos = Cache::get('star_data__'.$this->star_id."__page__".$page."__limit__".$limit."__order__".$order."__".$propClass);
		}
		$ret['videos'] = $retVideos;
		if($propClass == "featured" && $limit > count($retVideos)) {
			$idDatas = $this->getToSearch($limit-count($retVideos), 1, "", "");
			$ret['videos'] = array_merge($idDatas['videos'], $retVideos);
			$ret['count'] = count($ret['videos']);
		}
		return $ret;
	}
	
	public static function getStarsNameStarsId() {
	
		if (!Cache::has('stars_starname_starid_all')) {
			$datas = self::orderBy('star_name')->get(array('star_id', 'star_name'))->toArray();
			Cache::add('stars_starname_starid_all', $datas, 2);
			return $datas;
		}
		return Cache::get('stars_starname_starid_all');
	}
	
	public static function getStarsNaneStarsIdToStringFromFirstChar($text) {
		$query = self::where('star_name', 'like', $text.'%');
		$query->orderBy('star_name')->get(array('star_id', 'star_name'))->toArray();
		return $datas;
	}
	
	public static function getStarsNameStarsIdToString($text) {
		$query = self::where('star_name', 'like', '%'.$text.'%');
		$datas = $query->orderBy('star_name')->get(array('star_id', 'star_name'))->toArray();
		return $datas;
	}
}