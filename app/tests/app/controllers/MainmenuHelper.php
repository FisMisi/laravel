<?php

class MainmenuHelper extends BaseController {


	public static function savemenu() {
		$datas = Input::all();
		
		
		if($datas['mainmenu_id'] == 0) {
			$menu = new Mainmenu();
		} else {
			$menu = Mainmenu::find($datas['mainmenu_id']);
		}
		
		if (strlen($datas['name']) == 0 || strlen($datas['title']) == 0 ) {
			return Redirect::to("/administrator/menus/".$datas['mainmenu_id']);
		}
		
		if (strlen($datas['href']) == 0 && strlen($datas['onclick']) == 0 ) {
			return Redirect::to("/administrator/menus/".$datas['mainmenu_id']);
		}
		
		$menu->name = $datas['name'];
		$menu->title = $datas['title'];
		$menu->href = strlen($datas['href']) == 0 ? "javascript:void(0);" : $datas['href'];
		$menu->onclick = $datas['onclick'];
		$menu->target = $datas['target'];
		if (strlen($datas['pos']) != 0) {
			$menu->pos = $datas['pos'];
		}
		
		if (isset($datas['active']) && $datas['active'] == '1') {
			$menu->active = 1;
		} else {
			$menu->active = 0;
		}
		
		$menu->save();
		return Redirect::to("/administrator/menus/".$menu->mainmenu_id);
		
	}
	
	public static function subFunc($datas) {
		$datas['view'] = 'helper.admin.'.'mainmenu'.'.modify';
		if ($datas['id'] == 0) {
			$datas['helperData']['new'] = 1;
		} else {
			$datas['helperData']['new'] = 0;
			$datas['helperData']['menu'] = Mainmenu::find($datas['id']);
		}
		return $datas;
	}
	

	public static function getAdminDatas($datas) {
	
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['view'] = 'helper.admin.'.'mainmenu'.'.list';
		$datas['styleCss'] = array();
		$datas['jsLinks'] = array();
		
		if (isset($datas['id'])) return self::subFunc($datas);
		
		$datas['helperData']['list'] = Mainmenu::all();
		
		return $datas;
	}
}