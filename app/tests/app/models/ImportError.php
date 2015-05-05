<?php
require_once app_path()."/controllers/interfaces/SeoUrlSlug.php";

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class ImportError extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'import_errors';
	protected $primaryKey = 'id';
	
	
}