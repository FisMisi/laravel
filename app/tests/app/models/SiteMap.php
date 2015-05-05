<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class SiteMap extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'sitemaps';
	protected $primaryKey = 'sitemap_id';
	
	
	
	
}