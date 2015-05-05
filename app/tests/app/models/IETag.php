<?php
require_once app_path()."/controllers/interfaces/SeoUrlSlug.php";

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;


class IETag extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'i_e_tags';
	protected $primaryKey = 'i_e_tag_id';
}