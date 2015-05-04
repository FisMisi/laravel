<?php
require_once app_path()."/controllers/interfaces/SeoUrlSlug.php";

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;


class VideoITag extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'videos_i_tags';
	protected $primaryKey = 'videos_i_tag_id';
}