<?php
require_once app_path()."/controllers/interfaces/SeoUrlSlug.php";

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Partner extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'partners';
	protected $primaryKey = 'partner_id';


	public static function getIdToName($partnerName) {
		$partner = self::where('partner_name', '=', $partnerName)->first();
		if (is_null($partner)) {
			return 1;
		}
		return $partner->partner_id;
	}
	
}