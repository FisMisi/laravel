<?php
require_once app_path()."/controllers/interfaces/SeoUrlSlug.php";

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;


class VideoETag extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'videos_e_tags';
	protected $primaryKey = 'videos_e_tag_id';
	
}