<?php

class UnderHelper extends BaseController {

	public static function getViewDatas($datas) {
		
		$datas['view'] = 'helper.over18.under';
		$datas['pagetitle'] = "Under 18";
		$datas['helperData'] = "";
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['styleCss'] = array();
		$datas['jsLinks'] = array();
		return $datas;
	}


}