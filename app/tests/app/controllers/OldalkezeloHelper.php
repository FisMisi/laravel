<?php

class OldalkezeloHelper extends BaseController {


	public function modcontainer() {
		$modul = Input::get('modul');
		$hClass = Input::get('helper_class');
		$hFunction = Input::get('helper_function');
		$routing = Routing::find(Input::get('routing_id'));
		if (is_null($modul) || is_null($routing)) {
			return Redirect::to('administrator/oldalkezelo/'.Input::get('routing_id'));
		}
		
		
		
		if ($modul != 'Custom') {
			$modulObject = Modul::where('modul_name', 'like', $modul)->first();
			if(is_null($modulObject)) {
				$hClass = $modulObject->helper;
				$hFunction = $routing->need_admin_auth ? 'getAdminDatas' : 'getViewDatas';
			}
		}
		
		if (is_null($hClass) || is_null($hFunction)) {
			return Redirect::to('administrator/oldalkezelo/'.Input::get('routing_id'));
		}
		
		
		
		$content = Content::find(Input::get('content_id'));
		if (Input::get('container_name') == null || 
			Input::get('container_name') == "" 
		) {
			$content->delete();
			return Redirect::to('administrator/oldalkezelo/'.Input::get('routing_id'));
		} 
		
		$content->routing_id = Input::get('routing_id');
		$content->container_name = Input::get('container_name');
		if (!is_numeric(Input::get('pos')) || is_null(Input::get('pos'))) {
			$content->pos = 999;
		} else {
			$content->pos = Input::get('pos');
		}
		$content->modul = $modul;
		$content->helper_class = $hClass;
		$content->helper_function = $hFunction;
		#$content->helper_path = Input::get('helper_path');
		$content->helper_data_json = Input::get('helper_data_json');
		$content->save();
		return Redirect::to('administrator/oldalkezelo/'.$content->routing_id);
		
	}

	public function addcontainer() {
		if (Input::get('container_name') == null || 
			Input::get('container_name') == "" 
		) {
			return Redirect::to('administrator/oldalkezelo/'.Input::get('routing_id'));
		}
		
		$modul = Input::get('modul');
		$hClass = Input::get('helper_class');
		$hFunction = Input::get('helper_function');
		$routing = Routing::find(Input::get('routing_id'));
		if (is_null($modul) || is_null($routing)) {
			return Redirect::to('administrator/oldalkezelo/'.Input::get('routing_id'));
		}
		
		if ($modul != 'Custom') {
			$modulObject = Modul::where('modul_name', 'like', $modul)->first();
			if(is_null($modulObject)) {
				return Redirect::to('administrator/oldalkezelo/'.Input::get('routing_id'));
			}
			$hClass = $modulObject->helper;
			$hFunction = $routing->need_admin_auth ? 'getAdminDatas' : 'getViewDatas';
		}
		
		if (is_null($hClass) || is_null($hFunction)) {
			return Redirect::to('administrator/oldalkezelo/'.Input::get('routing_id'));
		}
		
		$content = new Content();
		$content->routing_id = Input::get('routing_id');
		$content->container_name = Input::get('container_name');
		if (!is_numeric(Input::get('pos')) || is_null(Input::get('pos'))) {
			$content->pos = 999;
		} else {
			$content->pos = Input::get('pos');
		}
		
		$content->modul = $modul;
		$content->helper_class = $hClass;
		$content->helper_function = $hFunction;
		#$content->helper_path = Input::get('helper_path');
		$content->helper_data_json = Input::get('helper_data_json');
		$content->save();
		return Redirect::to('administrator/oldalkezelo/'.$content->routing_id);
	}

	public function modify() {
		if (Input::get('routing_id') == 0) {
			$routing = new Routing;
			if (Input::get('routing_path') == null || 
				Input::get('layout_name') == null || 
				Input::get('routing_name') == null ||
				Input::get('routing_path') == "" || 
				Input::get('layout_name') == ""
			) {
			
				return Redirect::to('administrator/oldalkezelo/'.Input::get('routing_id'));
			}
		} else {
			$routing = Routing::find(Input::get('routing_id'));
			
			if (Input::get('routing_path') == null || 
				Input::get('layout_name') == null || 
				Input::get('routing_path') == "" || 
				Input::get('layout_name') == "" 
			) {
				#$routing->delete();#nem torlunk feluletrol route-ot max inaktivalunk
				#return Redirect::to('administrator/oldalkezelo/'.$routing->routing_id);
				return Redirect::to('administrator/oldalkezelo/'.$routing->id);
			}
		}
		
		$routing->routing_path = Input::get('routing_path');
		$routing->layout_name = Input::get('layout_name');
		$routing->routing_name = Input::get('routing_name');
		
		$routing->active = Input::get('active') ?: 0;
		$routing->needover18 = Input::get('needover18') ?: 0;
		$routing->need_auth = Input::get('need_auth') ?: 0;
		$routing->save();
		#return Redirect::to('administrator/oldalkezelo/'.$routing->routing_id);
		return Redirect::to('administrator/oldalkezelo/'.$routing->id);
	}

	protected static function subFunc($datas) {
		$datas['helperDataJson'] = 'helperDataJson';
		#$datas['helperData']['routing'] = Routing::where('routing_id', $datas['id'])->first();
		$datas['helperData']['routing'] = Routing::where('id', $datas['id'])->first();
		$datas['helperData']['contents'] = Content::where('routing_id', $datas['id'])->orderBy('container_name', 'ASC')->orderBy('pos', 'ASC')->get();
		$datas['view'] = 'helper.admin.'.'oldalkezelo'.'.modify';
		$datas['helperData']['layouts'] = Layout::where('can_public', 1)->get();
		$datas['helperData']['modules'] = Modul::where('has_public', 1)->get();
		
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = 'Oldal szerkesztése';
		}return $datas;
	}
	
	public static function getAdminDatas($datas) {
		$datas['view'] = 'helper.admin.'.'oldalkezelo'.'.list';
		
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		if (isset($datas['id'])) {
			return self::subFunc($datas);
		} 
		
		$limit = 20;
		if(isset($_GET['limit'])) {
			$datas['helperData']['limit'] = $_GET['limit'];
			$limit = $_GET['limit'];
		} else {
			$datas['helperData']['limit'] = 20;
		}
		
		$page = 1;
		if (isset($_GET['page'])) {
			$datas['helperData']['page'] = $_GET['page'];
			$page = $_GET['page'];
		} else {
			$datas['helperData']['page'] = 1;
		}
		
		$count = Routing::where('system_route', 0)->count();
		$query = Routing::where('system_route', 0);
		$query->take($limit);
		if ($page > 1) {
			$skip = ($page-1)*$limit;
			$query->skip($skip);
		}
		$routings = $query->orderBy('id', 'Desc')->get();
		$datas['helperData']['needPager'] = $count/$limit > 1 ? 1 : 0;
		$datas['helperData']['pagerOptions'] = ceil($count/$limit);
		
		$datas['pagetitle'] = null;
		#$routings = Routing::where('system_route', 0)->orderBy('routing_id', 'Desc')->get();
		
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['helperData']['routings'] = $routings;
		
		return $datas;
	}
}