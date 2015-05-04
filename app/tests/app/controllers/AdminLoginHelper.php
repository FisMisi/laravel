<?php

class AdminLoginHelper extends BaseController {

	public static function getViewDatas($datas) {
		$datas['view'] = 'helper.admin.login';
		
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = "Jelentkezzen be!";
		}
		
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		
		$datas['helperData'] = null;
		
		$datas['helperDataJson'] = 'helperDataJson';
		
		return $datas;
	}
	
}