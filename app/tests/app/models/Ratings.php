<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Ratings extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'ratings';
	protected $primaryKey = 'rating_id';
	public $timestamps = false;
	
	public static function canRating($videoId) {
		$sessionId = Session::getId();
		
		$ratingSession = self::where('video_id', '=', $videoId)->where('session_id', '=', $sessionId)->first();
		if ($ratingSession != null){ 
			return false;
		}
		
		if (Auth::check()) {
			$userId = Auth::user()->user_id;
			$ratingUser = self::where('video_id', '=', $videoId)->where('user_id', '=', $userId)->first();
			if ($ratingUser != null) {
				return false;
			}
		}
		return true;
	}
	
	public static function setRating($videoId, $rating) {
		$ratings = new self();
		$ratings->session_id = Session::getId();
		$ratings->video_id = $videoId;
		$ratings->ratings = $rating;
		$ratings->timestamps = false;
		if (Auth::check()) {
			$ratings->user_id = Auth::user()->user_id;
		}
		
		$ratings->save();
		
		$video = Video::find($videoId);
		$video->rating_number = $video->rating_number+1;
		$video->rating_number_l = $video->rating_number_l+1;
		$video->sum_rating = $video->sum_rating + $rating;
		$video->sum_rating_l = $video->sum_rating_l + $rating;
		$video->rating = round($video->sum_rating/$video->rating_number, 2);
		$video->rating_l = round($video->sum_rating_l/$video->rating_number_l, 2);
		$video->save();	
	}
}