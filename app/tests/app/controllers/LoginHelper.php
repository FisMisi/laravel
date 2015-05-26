<?php

class LoginHelper extends BaseController {

	public static function getViewDatas($datas) {
		$datas['view'] = 'helper.login.default';
		
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = "Jelentkezzen be!";
		}
		
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		
		$datas['helperData'] = null;
		$error = Session::get('error');
		$datas['helperData']['error'] = $error;
		$datas['helperDataJson'] = 'helperDataJson';
		
		return $datas;
	}
	
}