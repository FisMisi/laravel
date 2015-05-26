<?php

class AdminMenuHelper extends BaseController {

	public static function getAdminDatas($datas) {
		$datas['view'] = 'helper.admin.menu';
		
		$datas['styleCss']["URL::asset('css/adminmenu.css')"] = URL::asset('css/adminmenu.css');
		
		$datas['jsLinks'] = array();
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = "Admin Main";
		}
		$modules = Modul::orderBy("modul_id")->get();
		
		if (count($modules)) {
			$datas['helperData']['modules'] = $modules;
		} else {
			$datas['helperData']['modules'] = null;
		}
		$datas['helperData']['actualModul'] = 0;
		foreach($modules as $module) {
			if (strpos(Route::currentRouteName(), $module->admin_route) !== false) {
				$datas['helperData']['actualModul'] = $module->modul_id;
			}
		}
		$datas['helperDataJson'] = 'helperDataJson';
		
		return $datas;
	}
	
}