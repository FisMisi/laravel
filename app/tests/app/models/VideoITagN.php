<?php
require_once app_path()."/controllers/interfaces/SeoUrlSlug.php";

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;


class VideoITagN extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'videos_i_tags_n';
	protected $primaryKey = 'videos_i_tag_id';
}