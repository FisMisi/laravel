<?php
require_once app_path()."/controllers/interfaces/SeoUrlSlug.php";

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class TmpVideos extends Eloquent implements RemindableInterface {

	use RemindableTrait;

	protected $table = 'tmp_videos';
	protected $primaryKey = 'video_id';

	public static function getState0($limit) {
		$query = self::where('state', '=', 0);
		$query->take($limit);
		return $query->get(array('video_id', 'base_video_id'))->toArray();
	}
}