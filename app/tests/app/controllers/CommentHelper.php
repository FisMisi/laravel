<?php

class CommentHelper extends BaseController {

	private function generatePage($comments) {
		$ret = "";
		foreach($comments as $c) {
			$ret.= '<div class="comments">
				<div class="user">'.$c['nick'].'</div>
				<div class="comment">'.$c['comment'].'</div>
				<div class="time">'.$c['created_at'].'</div><br /><br />
			</div>';
		}
		
		return array('ret' => $ret, 'count' => count($comments));
	}
	
	public function savecomment() {
		$data = Input::all();
		$page = $data['p'];
		$videoId = $data['v'];
		$comment = $data['c'];
		if(Auth::Check() && Auth::user()->nick !== null && Auth::user()->nick !== '') {				
			$commentObj = new Comment();
			$commentObj->video_id = $videoId;
			$user = Auth::User();
			$commentObj->user_id = $user->user_id;
			$commentObj->comment = $comment;
			$commentObj->active = 1;
			$commentObj->save();
			$page = 1;
		}
		$comments = Comment::getCommentsToVideoId($videoId, $page);
		$commentNum = Comment::getCommentsNumToVideoId($videoId);
		$ret['pages'] = ceil((float)$commentNum/7);
		if ($ret['pages'] == 0) $ret['pages'] = 1;
		$comret = $this->generatePage($comments);
		$ret['comments'] = $comret['ret'];
		$ret['actualpage'] = $page;
		return Response::json( array('ret' => $ret,'count' => $commentNum) );
	}

	public function getPage() {
		//return Response::json( "Ez fut le" );
		$data = Input::all();
		$page = $data['p'];
		$videoId = $data['v'];
		$comments = Comment::getCommentsToVideoId($videoId, $page);
		$comret = $this->generatePage($comments);
		$commentNum = Comment::getCommentsNumToVideoId($videoId);
		$ret['pages'] = ceil((float)$commentNum/7);
		$ret['comments'] = $comret['ret'];
		$ret['actualpage'] = $page;
		return Response::json( $ret );
	}

	public function modify() {
		if (is_null(Input::get('comment_id'))) return Redirect::to("administrator/comment");
		
		if (!Auth::check()) return Redirect::to("administrator/comment");
		
		$user = Auth::User();
		if (!$user->admin && $user->active) return Redirect::to("administrator/comment");
		
		$comment = Comment::find(Input::get('comment_id'))->first();
		if (is_null($comment)) return Redirect::to("administrator/comment");
		
		if ($comment->active || !Input::get('active')) {
			$comment->inactive_reason = Input::get('inactive_reason');
			$comment->inactive_user_id = $user->user_ID;
		}
		$comment->active = Input::get('active');
		$comment->comment = Input::get('comment');
		$comment->save();
		return Redirect::to("administrator/comment/".$comment->comment_id);
		
	}

	public static function subFunc($datas) {
		$datas['view'] = 'helper.admin.'.'comment'.'.modify';
		$datas['helperData']['comment'] = Comment::find($datas['id']);
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = 'Comment szerkesztése';
		}
		$datas['helperData']['user'] = null;
		$datas['helperData']['inactive_user'] = null;
		$datas['helperData']['video'] = null;
		if ($datas['helperData']['comment']->user_id) {
			$datas['helperData']['user'] = User::find($datas['helperData']['comment']->user_id);
		}
		
		if ($datas['helperData']['comment']->inactive_user_id) {
			$datas['helperData']['inactive_user'] = User::find($datas['helperData']['comment']->user_id);
		}
		
		if ($datas['helperData']['comment']->video_id) {
			$datas['helperData']['video'] = Video::find($datas['helperData']['comment']->video_id);
		}
		
		
		return $datas;
	}
	
	public static function getViewDatas($datas) {
		
		$datas['styleCss'] = array();
		$datas['jsLinks']["URL::asset('js/comment.js')"] = URL::asset('js/comment.js');
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['view'] = 'helper.'.'comment'.'.default';
		
		$commentNum = Comment::getCommentsNumToVideoId($datas['videoId']);
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = "Comments";
		}
		$datas['helperData']['actualpage'] = 1;
		$datas['helperData']['commentnum'] = $commentNum;
		$datas['helperData']['video_id'] = $datas['videoId'];
		$datas['helperData']['pages'] = ceil((float)$commentNum/7);
		if($datas['helperData']['pages'] == 0) $datas['helperData']['pages'] = 1;
		$datas['helperData']['comments'] = Comment::getCommentsToVideoId($datas['videoId']);
		if ($datas['helperData']['pages'] > 1) {
			$datas['helperData']['needNext'] = 1;
			$datas['helperData']['next'] = 2;
		} else {
			$datas['helperData']['needNext'] = 0;
		}
		if(Auth::Check()) {
			$datas['helperData']['hasUser'] = 1;
			if(Auth::user()->nick !== null && Auth::user()->nick !== '') {
				$datas['helperData']['hasNick'] = 1;
			} else {
				$datas['helperData']['hasNick'] = 0;
			}
		} else {
			$datas['helperData']['hasUser'] = 0;
			$datas['helperData']['hasNick'] = 0;
		}
		
		return $datas;
	}

	public static function getAdminDatas($datas) {
		$datas['view'] = 'helper.admin.'.'comment'.'.list';
		
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		$datas['helperDataJson'] = 'helperDataJson';
		
		if (isset($datas['id'])) {
			return self::subFunc($datas);
		}
		
		
		$active = 2;
		if (isset($_GET['active'])) {
			$active = $_GET['active'];
		}
		$datas['helperData']['active'] = $active;
		
		$user_id = null;
		if (isset($_GET['user'])) {
			$user_id = $_GET['user'] == '0' ? null : $_GET['user'];
		}
		$datas['helperData']['user'] = $user_id;
		
		$video_id = null;
		if (isset($_GET['video'])) {
			$video_id = $_GET['video'] == '0' ? null : $_GET['video'];
		}
		$datas['helperData']['video'] = $video_id;
		
		$limit = 20;
		if(isset($_GET['limit'])) {
			$limit = $_GET['limit'];
		}
		$datas['helperData']['limit'] = $limit;
		
		$page = 1;
		if(isset($_GET['page'])) {
			$page = $_GET['page'];
		}
		$datas['helperData']['page'] = $page;
		
		$category_id = null;
		if (isset($_GET['category'])) {
			$category_id = $_GET['category'] == '0' ? null : $_GET['category'];
		}
		$datas['helperData']['category'] = $category_id;
		
		$idDatas = Comment::getCommentListByDatas($active, $video_id, $user_id, $category_id, $limit, $page);
		$count = $idDatas['count'];
		$comment = $idDatas['comments'];
		
		$datas['helperData']['needPager'] = $count/$limit > 1 ? 1 : 0;
		$datas['helperData']['pagerOptions'] = ceil($count/$limit);
		
		$categoryList = array();
		$datas['helperData']['categoryList'] = $categoryList;
		$datas['helperData']['list'] = $comment;
		return $datas;
	}
}