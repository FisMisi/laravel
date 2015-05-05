<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class PrivateVideo extends Eloquent implements RemindableInterface {

	use RemindableTrait;

	protected $table = 'private_videos';
	protected $primaryKey = 'id';
	
	
	public static function addVideoToUser($videoId, $userId) {
		$pv = self::where('video_id', '=', $videoId)->where('user_id', '=', $userId)->get();
		if (is_null($pv) || $pv == array()) {#ekkor nincs még
			$newPv = new self();
			$newPv->user_id = $userId;
			$newPv->video_id = $videoId;
			$newPv->save();
		}
		return true;
	}
	
	public static function getListToFront($userId, $limit, $page) {
		if ($page > 0) {
			$page--;
		}
		$query = Video::join('private_videos', 'videos.video_id', '=', 'private_videos.video_id');
		$query->join('see_videos', 'see_videos.video_id', '=', 'videos.video_id');
		$query->where('private_videos.user_id', '=', $userId);
		$query->where('videos.active', '=', 1)->where('videos.active2', '=', 1);
		$query->take($limit);
		if ($page > 0) {
			$query->skip($page*$limit);
		}
		
		$countObj = self::join('videos', 'videos.video_id', '=', 'private_videos.video_id')->where('videos.active', '=', 1)->where('videos.active2', '=', 1)->where('user_id', '=', $userId)->selectRaw('count(videos.video_id) as c')->get();
		$count = $countObj[0]->c;
		$query->orderBy('private_videos.id', 'desc');
		$getArray = 'videos.video_id, videos.video_name, default_thumb, videos.video_seo_name, length, sum_rating, rating_number, videos.rating, see_videos.see_count';
		$pvList = $query->selectRaw($getArray)->get();
		$retVideos = Video::getDatasToPublicFromVideosALT($pvList);
		$ret = array();
		$ret['count'] = $count;
		$ret['videos'] = $retVideos;
		return $ret;
	}
	
}