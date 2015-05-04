<?php

class Over18Helper extends BaseController {

	public static function getViewDatas($datas) {
		$datas['view'] = 'helper.over18.default';
		$datas['pagetitle'] = "Are You Over 18?";
		$datas['helperData'] = array();
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['styleCss'] = array();
		$datas['jsLinks'] = array();
		return $datas;
	}


}