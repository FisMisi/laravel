<?php
require_once app_path()."/controllers/interfaces/SeoUrlSlug.php";

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Tmp2Videos extends Eloquent implements RemindableInterface {

	use RemindableTrait;
        public    $timestamps = false;
	protected $table = 'tmp2_videos';
	protected $primaryKey = 'id';
}
