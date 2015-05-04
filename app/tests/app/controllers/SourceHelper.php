<?php

/**
 * Helper ososztaly (leginkabb csak manko a kialakitashoz)
 */
class SourceHelper extends BaseController {

	protected $modulName = 'X';
	
	public static function setModulName($modulName) {
		$this->modulName = $modulName;
	}

	public static function getAdminDatas($datas) {
		$datas['view'] = 'helper.admin.'.$modulName;
		
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['helperData'] = null;
		
		return $datas;
	}
	
	public static function getViewDatas($datas) {
		
		$datas['view'] = 'helper.'.$this->modulName.'.default';
		
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['helperData'] = null;
		
		return $datas;
	}
}