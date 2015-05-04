<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Comment extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'comments';
	protected $primaryKey = 'comment_id';

	/**
	 * Comment videóhoz társítása, validálással
	 *
	 */
	public static function addCommentToVideoId($videoId, $commentText) {
		if (!Auth::check()) 
			return false;
		
		$user = Auth::User();
		
		if (!$user->active || (!$user->admin && !$user->confirmed)) 
			return false;
		
		$comment = new self();
		$comment->video_id = $videoId;
		$comment->comment = $commentText;
		$comment->user_id = $user->user_id;
		$comment->active = 1;
		
		return $comment->save();
	}
	
	/**
	 * Adott comment inaktiválása, validálással
	 *
	 */
	public static function disableCommentByCommentId($commentId, $reason) {
		if (!Auth::check()) 
			return false;
		
		$inactiveUser = Auth::User();
		
		if (!$inactiveUser->active || !$inactiveUser->admin) 
			return false;
		
		
		$comment = self::find($commentId);
		
		if (is_null($comment) || is_null($comment->comment_id)) 
			return false;
		
		
		$comment->active = 0;
		$comment->inactive_reason = $reason;
		$comment->inactive_user = $inactiveUser->user_id;
		
		return $comment->save();
	}
	
	/**
	 * videohoz tartozo commentek elkérése (adott oldalra nézve), elsõsorban public felhasználásra
	 *
	 */
	public static function getCommentsToVideoId($videoId, $page = 1, $limit = 7) {
		$offset = ($page-1)*$limit;
		$getArray = array();
		$getArray = array('users.user_id', 'email', 'nick', 'comment', 'comments.created_at', 'comment_id', 'comments.video_id');
		return self::join('users', 'users.user_id', '=', 'comments.user_id')->where('comments.active', 1)->where('video_id', '=', $videoId)->take($limit)->skip($offset)->orderBy('comment_id', 'desc')->get($getArray)->toArray();
	}
	
	public static function getCommentsNumToVideoId($videoId	) {
		return self::where('active', 1)->where('video_id', '=', $videoId)->count();
	}
	
	
	/**
	 * comment tartalmának módosítása, validálással
	 *
	 */
	public static function modifyCommentById($commentId, $commentText) {
		if (!Auth::check()) 
			return false;
		
		$user = Auth::User();
		
		$comment = self::find($commentId);
		
		if (is_null($comment) || is_null($comment->comment_id)) 
			return false;
		
		if ($comment->user_id != $user->user_id && (!$user->admin || !$user->active)) 
			return false;
		
		$comment->comment = $commentText;
		
		return $comment->save();
		
	}
	
	/**
	 * Commentek listája, szûrésnek megfelelõen
	 *
	 * Elsõsorban admin felület számára!
	 *
	 */
	public static function getCommentListByDatas($active = 2, $video_id = null, $user_id = null, $category_id = null, $limit = 20, $page = 1) {
		
		if ($page > 0) {
			$page--;
		}
	
		if (is_null($active) || $active > 1) {
			$active = 2;
		}
		
		$query = self::join('users as user', 'user.user_id', '=', 'comments.user_id');
		$query2 = self::join('users as user', 'user.user_id', '=', 'comments.user_id');
		$query->join('videos', 'videos.video_id', '=', 'comments.video_id');
		$query2->join('videos', 'videos.video_id', '=', 'comments.video_id');
		$query->leftJoin('users as inactive_user', 'inactive_user.user_id', '=', 'comments.inactive_user_id');
		$query2->leftJoin('users as inactive_user', 'inactive_user.user_id', '=', 'comments.inactive_user_id');
		
		if (!is_null($category_id)) {
			$query->where('videos.category_id', '=', $category_id);
			$query2->where('videos.category_id', '=', $category_id);
		}
		if (!is_null($video_id)) {
			$query->where('comments.video_id', '=', $video_id);
			$query2->where('comments.video_id', '=', $video_id);
		}
		if (!is_null($user_id)) {
			$query->where('comments.user_id', '=', $user_id);
			$query2->where('comments.user_id', '=', $user_id);
		}	
		if ($active != 2) { 
			$query->where('comments.active', $active);
			$query2->where('comments.active', $active);
		}
		$query->take($limit);
		if($page > 0) {
			$query->skip($page*$limit);
		}
		
		$getArray = array(	'comment_id', 
							'comments.active', 
							'comment', 
							'user.email as user_email', 
							'user.user_id as user_id',
							'inactive_user.email as inactive_email', 
							'comments.inactive_reason',
							'video_name');
							
		$ret = array();
		$ret['comments'] = $query->get($getArray)->toArray();
		$ret['count'] = $query2->count();
		return $ret;
	}

}