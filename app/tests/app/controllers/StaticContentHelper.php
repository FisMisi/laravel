<?php

class StaticContentHelper extends BaseController {


	public function modify() {
	
		if (Input::get('static_content_id') == 0) {
			$class = Input::get('class_select') == 'new_class_sc' ? Input::get('sc_name') : Input::get('class_select');
			if (strlen($class) == 0) {
				return Redirect::to('administrator/staticcontent/'.Input::get('static_content_id'));
			}
		} 
		
		if (Input::get('language') === 0 || Input::get('language') === '0') {
				return Redirect::to('administrator/staticcontent/'.Input::get('static_content_id'));
		}
		
		if ( strlen(Input::get('title')) == 0 || strlen(Input::get('content')) == 0 ) {
			#page reload!
			return Redirect::to('administrator/staticcontent/'.Input::get('static_content_id'));
		}
		
		if (Input::get('static_content_id') == 0) {#ekkor inserte
			$staticContent = new StaticContent;
			$staticContent->create_user_id = Auth::user()->user_id;
			$staticContent->class = $class;
			$staticContent->language = (string)Input::get('language');
		} else {#ekkor update
			$staticContent = StaticContent::find(Input::get('static_content_id'));
			$staticContent->modify_user_id = Auth::user()->user_id;
		}
		
		
		$staticContent->title = Input::get('title');
		$staticContent->active = Input::get('active') == 1 ? 1 : 0;
		$staticContent->content = Input::get('content');
		
		$staticContent->save();
		return Redirect::to('administrator/staticcontent/'.$staticContent->static_content_id);
	}

	public static function subFunc($datas) {
	
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['helperData']['sc'] = StaticContent::find($datas['id']);
		$script = 'var editor;KindEditor.ready(function(K) {editor = K.create(\'textarea[name="content"]\', {'.
		'allowFileManager : true});});KindEditor.ready(function(K) {editor = K.create(\'textarea[name="content"]\','.
		' {langType : "en"});});';
		$datas['scripts'][$script] = $script;
		
		$script2 = StaticContent::getFreeLangToSc();
		$datas['scripts'][$script2] = $script2;
		$datas['styleCss']["URL::asset('kindeditor-4.1.10/themes/default/default.css')"] = URL::asset('kindeditor-4.1.10/themes/default/default.css');
		
		$datas['jsLinks']["URL::asset('kindeditor-4.1.10/kindeditor-min.js')"] = URL::asset('kindeditor-4.1.10/kindeditor-min.js');
		$datas['jsLinks']["URL::asset('kindeditor-4.1.10/lang/en.js')"] = URL::asset('kindeditor-4.1.10/lang/en.js');
		
		$datas['helperData']['langs'] = Language::getLangList(false);
		
		$datas['helperData']['classes'] = StaticContent::getNotFullLangClass(true);
		
		$datas['view'] = 'helper.admin.'.'staticcontent'.'.modify';
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = 'Statikus tartalom szerkesztése';
		}
		return $datas;
	}
	
	public static function getPagerDatas($func = 'getViewDatas') {
		$ret = array();
		if ($func == 'getViewDatas') {	
			$ret[0]['title'] = 'SC name';
			$ret[0]['name'] = 'class';
			$ret[0]['type'] = 'list';
			$ret[0]['src'] = StaticContent::getClasses();
			$ret[1]['title'] = 'Need Title';
			$ret[1]['name'] = 'needTitle';
			$ret[1]['type'] = 'boolean';
		}
		return $ret;
	}
	
	
	public static function getViewDatas($datas) {
		if (isset($datas['lang'])) {
			$lang = $datas['lang'];
		} else {
			$lang = 'en';
		}
		
		$datas['view'] = 'helper.staticcontent.default';
		$datas['styleCss'] = array();
		
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['helperData']['sc'] = StaticContent::getLangStaticContent($datas['class'], $lang);
		if (is_null($datas['helperData']['sc'])) {
			$datas['helperData']['sc'] = StaticContent::getLangStaticContent($datas['class'], 'en');
		}
		$datas['helperData']['needTitle'] = isset($datas['needTitle']) ? $datas['needTitle'] : 1;
		
		$datas['jsLinks'] = array();
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = $datas['helperData']['sc']->title;
		}
		return $datas;
	}

	public static function getAdminDatas($datas) {
		$datas['view'] = 'helper.admin.'.'staticcontent'.'.list';
		
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		
		if (isset($datas['id'])) {
			return self::subFunc($datas);
		}
		$lang = null;
		
		if (isset($_GET['lang']) && $_GET['lang'] != 'no'){
			$lang = $_GET['lang'];
			$datas['helperData']['lang'] = $_GET['lang'];
		} else {
			$datas['helperData']['lang'] = 'no';
		}
		
		$active = null;
		if (isset($_GET['active'])) {
			$active = $_GET['active'];
			$datas['helperData']['active'] = $_GET['active'];
		} else {
			$datas['helperData']['active'] = 2;
		}
		
		$limit = 20;
		if(isset($_GET['limit'])) {
			$datas['helperData']['limit'] = $_GET['limit'];
			$limit = $_GET['limit'];
		} else {
			$datas['helperData']['limit'] = $limit;
		}
		
		$page = 1;
		if(isset($_GET['page'])) {
			$datas['helperData']['page'] = $_GET['page'];
			$page = $_GET['page'];
		} else {
			$datas['helperData']['page'] = 2;
		}
		$idDatas = StaticContent::getDatasToAll($lang, $active, $limit, $page);
		$count = $idDatas['count'];
		$staticContents = $idDatas['scs'];
		$datas['helperData']['needPager'] = $count/$limit > 1 ? 1 : 0;
		$datas['helperData']['pagerOptions'] = ceil($count/$limit);
			
		$datas['helperData']['langs'] = Language::getLangList();
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['helperData']['list'] = $staticContents;
		return $datas;
	}

}