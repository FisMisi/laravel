<?php

class FelhasznalokezeloHelper extends BaseController {

	public function moduser() {
		$msg = '';
		$errors = array();
		#$errors = array('error4', 'error5', 'error6');
		$datas = Input::all();
		
		if(!isset($datas['i']) || $datas['i'] == '' || !Auth::check() || Auth::user()->user_id != $datas['i'] || (!Auth::user()->confirmed && !Auth::user()->admin) || !Auth::user()->active ) {
			return array('ferror' => 1, 'error' => array('KRIT ERROR'));
		}
		
		if (isset($datas['s']) && !in_array($datas['s'], array('2', '1'))) {
			return array('ferror' => 1, 'error' => array('KRIT ERROR'));
		}
		
		$user = User::find($datas['i']);
		if (isset($datas['n'])) {
			$user->nick = $datas['n'];
		}
		
		if (isset($datas['f'])) {
			$user->first_name = $datas['f'];
		}
		
		if (isset($datas['l'])) {
			$user->last_name = $datas['l'];
		}
		
		if (isset($datas['s'])) {
			$user->sex = $datas['s'];
		}
		
		$user->save();
		$msg = "Profil data change is success!";
		return array('ferror' => 0, 'error' => $errors, 'msg' => $msg);
	}

	public function modpasswd() {
		$msg = '';
		$errors = array();
		
		//$errors = array('error1', 'error2', 'error3');
		$datas = Input::all();
		
		if(!isset($datas['i']) || $datas['i'] == '' || !Auth::check() || Auth::user()->user_id != $datas['i']) {
			return array('ferror' => 1, 'error' => array('KRIT ERROR'));
		}
		
		if (!isset($datas['o']) || $datas['o'] == '') {
			$errors[] = "Need to add old password!";
		}
		
		if (!isset($datas['n']) || $datas['n'] == '') {
			$errors[] = "Need to add new password!";
		}
		
		if (!isset($datas['n2']) || $datas['n2'] == '' || $datas['n2'] != $datas['n']) {
			$errors[] = "Need to vertify new password!";
		}
		
		$oldPasswd = Auth::user()->password;
		if (!Hash::check($datas['o'], $oldPasswd)) {
			$errors[] = "Old password is incorrect!";
		}
		
		if (count($errors) == 0) {
			$user = User::find($datas['i']);
			$user->password = Hash::make($datas['n']);
			$user->save();
			$msg = "Password change is success!";
		}
		return array('ferror' => 0, 'error' => $errors, 'msg' => $msg);
	}

	public function modifyUser() {
		#var_dump(Input::all());
		#die();
		
		$actualIsSA = User::is_a_SA(Auth::user()->user_id);
		
		if (Input::get('user_id') == 1 && Auth::user()->user_id != 1)
			return Redirect::to("administrator/felhasznalokezelo/1");
		
		if (User::is_a_SA(Input::get('user_id')) && !$actualIsSA) 
			return Redirect::to("administrator/felhasznalokezelo/".Input::get('user_id'));
		
		if (!is_null(Input::get('admin')) && Input::get('user_id') != 0 )
			return Redirect::to("administrator/felhasznalokezelo/".Input::get('user_id'));
		
		if (!is_null(Input::get('admin')) && !$actualIsSA) 
			return Redirect::to("administrator/felhasznalokezelo/".Input::get('user_id'));
		
		
		if (is_null(Input::get('email'))) 
			return Redirect::to("administrator/felhasznalokezelo/".Input::get('user_id'));
		
		if (Input::get('user_id') == 0) {
			$user = new User;
		} else {
			$user = User::find(Input::get('user_id'));
		}
		
		
		if (!is_null(Input::get('admin'))) {
			$user->admin = Input::get('sa') == 1 ? 1 : Input::get('admin');
		}
		
		$user->confirmed = Input::get('confirmed') != null ? Input::get('confirmed') : $user->admin;
		if (!is_null(Input::get('active'))) {
			$user->active = Input::get('active');
		}
		if (!is_null(Input::get('inactive_reason'))) {
			$user->inactive_reason = Input::get('inactive_reason');
		}
		if (!is_null(Input::get('email'))) {
			$user->email = Input::get('email');
		}
		$user->nick = Input::get('nick');
		$user->first_name = Input::get('first_name');
		$user->last_name = Input::get('last_name');
		$user->save();
		
		if (Input::get('sa') == 1) {
			$ur = RightUser::where('user_id', $user->user_id)->where('right_id', 1)->first();
			if (!$ur) {
				$ur = new RightUser;
				$ur->user_id = $user->user_id;
				$ur->right_id = 1;
				$ur->save();
			} 
		}
		if (Input::get('sa') == 0 && !is_null(Input::get('sa'))) {
			$ur = RightUser::where('user_id', $user->user_id)->where('right_id', 1)->first();
			if ($ur) {
				$ur->delete();
			}
		}
		#var_dump("administrator/felhasznalokezelo/".$user->user_id);
		#die();
		return Redirect::to("administrator/felhasznalokezelo/".$user->user_id);
		
	}


	public static function subFunc($datas) {
		$datas['view'] = 'helper.admin.'.'felhasznalokezelo'.'.modify';
		$datas['helperData']['user'] = User::where('user_id', $datas['id'])->first();
		$datas['helperData']['userisSA'] = User::is_a_SA($datas['id']);
		if ($datas['id'] && $datas['helperData']['user']->admin && $datas['id'] != Auth::user()->user_id && !$datas['helperData']['AUisSA'])
			Redirect::to('/elso/public/index.php/administrator/felhasznalokezelo');
		
		return $datas;
	}
	
	public static function getViewDatas($datas) {
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['view'] = 'helper.'.'users'.'.default';
		$datas['styleCss'] = array();
		$datas['jsLinks'] = array();
		#var_dump(1);
		if (!Auth::check()) {
			#var_dump(2);
			if (!isset($_GET['c'])) {
			#	var_dump(3);
				return false;
			}
			
			$user = User::where('confirm_link', '=', $_GET['c'])->where('confirmed', '=', 0)->first();
			if (is_null($user)) {
			#	var_dump(4);
				return false;
			}
			#var_dump(5);
			$user->confirmed = 1;
			$user->save();
			#var_dump(6);
			Auth::loginUsingId($user->user_id);
                        #@todo: meed over18 session
			#var_dump(7);
		}
		if(Auth::user()->confirmed == 0) {
			#return false;
		}
		$idDatas['confirmed'] = Auth::user()->confirmed;
		$idDatas['admin'] =Auth::user()->admin;
		$idDatas['user_id'] = Auth::user()->user_id;
		$idDatas['email'] = Auth::user()->email;
		$idDatas['nick'] = Auth::user()->nick;
		$idDatas['firstname'] = Auth::user()->first_name;
		$idDatas['lastname'] = Auth::user()->last_name;
		$idDatas['sex'] = Auth::user()->sex;
		$datas['helperData'] = $idDatas;
		$datas['pagetitle'] = "Profil";
		
		return $datas;
	}
	
	public static function getAdminDatas($datas) {
		$adminList = 0;
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['view'] = 'helper.admin.'.'felhasznalokezelo'.'.list';
		
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		$datas['helperData']['actualUser'] = Auth::user();
		$datas['helperData']['AUisSA'] = User::is_a_SA(Auth::user()->user_id);
		
		if (isset($datas['id'])) return self::subFunc($datas);
		
		$actualRoute = Route::getCurrentRoute()->getPath();
		$actualRouteElements = explode('/', $actualRoute);
		if (is_array($actualRouteElements) && in_array('admin', $actualRouteElements)) $adminList = 1;
		$datas['helperData']['adminList'] = $adminList;
		$active = null;
		if (isset($_GET['active'])) {
			$active = $_GET['active'];
			$datas['helperData']['active'] = $_GET['active'];
		} else {
			$datas['helperData']['active'] = 2;
		}
		$confirmed = null;
		$sa = null;
		
		if ($adminList) {
			if (isset($_GET['sa'])) {
				$sa = $_GET['sa'];
				$datas['helperData']['sa'] = $_GET['sa'];
			} else {
				$datas['helperData']['sa'] = 2;
			}	
		} else {
			if (isset($_GET['confirmed'])) {
				$confirmed = $_GET['confirmed'];
				$datas['helperData']['confirmed'] = $_GET['confirmed'];
			} else {
				$datas['helperData']['confirmed'] = 2;
			}
		}
		
		$limit = 20;
		if(isset($_GET['limit'])) {
			$datas['helperData']['limit'] = $_GET['limit'];
			$limit = $_GET['limit'];
		} else {
			$datas['helperData']['limit'] = 20;
		}
		
		$page = 1;
		if(isset($_GET['page'])) {
			$datas['helperData']['page'] = $_GET['page'];
			$page = $_GET['page'];
		} else {
			$datas['helperData']['page'] = 1;
		}
		
		$idDatas = User::getUsersToDatas($adminList, $active, $confirmed, $sa, $limit, $page);
		$count = $idDatas['count'];
		$datas['helperData']['userList'] = $idDatas['users'];
		$datas['helperData']['needPager'] = $count/$limit > 1 ? 1 : 0;
		$datas['helperData']['pagerOptions'] = ceil($count/$limit);
		return $datas;
	}
}