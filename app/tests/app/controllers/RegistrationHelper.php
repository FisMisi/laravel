<?php

class RegistrationHelper extends BaseController {


	public function getviewtologin() {
		return View::make('helper.login.default', array('helperDataJson' => array('email' => array())))->render();
	}

	public function getviewtoreg() {
		return View::make('helper.login.registration', array('helperDataJson' => array('email' => array())))->render();
	}

	public function validreg($link) {
	$h = hash_hmac('sha1', date('Y-m-d h:i:s'), 'regvalid');
		
		$user = User::where('confirm_link', 'like', $link)->first();
		if(is_null($user)) {
			echo 'nincs ilyen link';
			die();
		}
		if ($user->confirmed) {
			Auth::loginUsingId($user->user_id);
		} else {
			if (strtotime($user->confirm_valid_time) > time()) {
				$user->confirmed = 1;
				$user->save();
				Auth::loginUsingId($user->user_id);
				echo 'regisztráció befejezve';
			} else {
				echo 'lejart link';
			}
		}
		
	}
	
	public function resendConfirmLink() {
		if (is_null(Input::get('email'))) {
			echo 'email megadása kötelező';
			return;
		}
		
		if ($this->sendConfirmLinkToEmail(Input::get('email'))) {
			echo 'email sikeresen újraküldve!';
			return;
		}
		echo 'nincs ilyen regisztált email!';
	}
	
	public function sendConfirmLinkToEmail($email) {
		$user = User::where('email', 'like', 'email')->first();
		if (is_null($user)) {
			return false;#if need
		} else {
			$this->sentConfirmLink($user);
			return true;#if need
		}
	}
	
	public function sentConfirmLink($user) {
		$user->confirm_link = hash_hmac('sha1', date('Y-m-d h:i:s'), 'regvalid');
		$user->confirm_valid_time = date('Y-m-d h:i:s', time()+3*86400);
		$user->save();
		$message = "Dear New Guest!\n\nTo active your registration, please click on the link below or copy it to your browser.\n";
		$message.= "http://liveruby.com/profil?c=".$user->confirm_link."\nThank you for your registration and enjoy our contents!\nGreetings: LiveRuby";
		mail($user->email, 'LiveRuby.com registration', $message);
		/*Mail::send('emails.html.teszt', array('user' => $user), function($message) {
			$message->from('reg@reg.com', 'Laravel');

			$message->to('paronai.tamas@gmail.com')->cc('paronai.tamas@ikron.hu');
		});*/
	}

	public function postreg() {
		$error = array();
		if (!strlen(Input::get('p'))) {
			$error[] = "Need to add password!";
		}
		
		if (!strlen(Input::get('e'))) {
			$error[] = "Need to add email!";
		}
		
		if (!strlen(Input::get('p2'))) {
			$error[] = "Need to vertify password!";
		}
		
		if (Input::get('p2') != Input::get('p')) {
			$error[] = "Need to add the same password!";
		}
		
		if (count($error)) {
			return array('error' => $error, 'msg' => '');
			#return Redirect::route('/registration')->with('error', $error)->withInput(Input::except('password', 'password2'));
		}
		
		$user = User::where('email', 'like', Input::get('e'))->first();
		if (!is_null($user)) {
			$error[] = "This email has a registration";
			return array('error' => $error, 'msg' => 'msg');
			#return Redirect::route('/registration')->with('error', $error)->withInput(Input::except('password', 'password2'));
		}
		
		$user = new User;
		$user->email = Input::get('e');
		$user->password = Hash::make(Input::get('p'));
		$user->save();
		Auth::loginUsingId($user->user_id);
		LogEvent::where('session_id', '=', Session::getId())->update(array("user_id" => $user->user_id));
		Ratings::where('session_id', '=', Session::getId())->update(array("user_id" => $user->user_id));
		$this->sentConfirmLink($user);
		return array('error' => '', 'msg' => 'Registration is success! Please vertify Yout email address!');
		#return Redirect::route('/registration')->with('error', array('sikeres regisztráció'))->withInput(Input::except('password', 'password2'));
	}

	public static function getViewDatas($datas) {
		$datas['view'] = 'helper.login.registration';
		
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = "Csatlakozzon egy elit klubbhoz!";
		}
		
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		
		$datas['helperData'] = array();
		$oldInput = Session::get('_old_input');
		if ($oldInput['email']) {
			$datas['helperData']['email'] = $oldInput['email'];
		} else {
			$datas['helperData']['email'] = '';
		}
		$datas['helperDataJson'] = 'helperDataJson';
		
		$error = Session::get('error');
		$datas['helperData']['error'] = $error;
		
		return $datas;
	}
	
}