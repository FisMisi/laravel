<?php
/**
 * a layoutokat vezerlo osztalyok ososztalya
 *
 * @author: Paronai Tamás
 */
class SourceController extends BaseController {

	protected $layout = 'layout';
	
	protected $numargs;
	protected $arg_list;
	
	#default ertekek, a többit a beepulo contenerek szedik ossze
	protected $jsLinks = array();
	protected $styleCss = array();
	protected $scripts = array();
	protected $pagetitle = "";
	protected $metaDatas = "";
	protected $viewData = array();
	
	protected $actualRoutePath = '';
	protected $actualRouteElements = array();
	protected $actualRouting;
	
	protected $contents = array();
	protected $usableDatas = array();
	protected $helperData = array();
	
	protected $helperGetDataFunction = 'getViewDatas';
	protected $actualLayoutName = 'full';
	
	protected $hasLang = false;

	protected $needOver18 = false;
	protected $dataToOtherContent = array();
	/**
	 * Ha nem nyilatkozott még, hogy elmult, akkor redirect
	 */
	protected function isOver18() {
		if (!Session::get('over18', 0)) {
			$ret = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : "/";
			Session::put('befoveOverUrl', $ret);
return Redirect::to('/over18');
			return Redirect::route('/over18');
		}
		return false;
	}
	
	/**
	 * over18 validálás beállítása Session-be, source url-re redirect
	 */
	public function setOver18() {
		Session::forget('over18');
		Session::put('over18', 1);
		if(Session::has('befoveOverUrl')) {
			$returnUrl = Session::get('befoveOverUrl');
			Session::remove('befoveOverUrl');
			return Redirect::to($returnUrl);
		} else {
		
		}
		return Redirect::to(Session::pull('/befoveOverUrl', '/'));
	}
	
	/*
	 * ha be van jelentkezve, akkor kijelentkeztetjük
	 */
	public function logout() {
		if (!Auth::check()) {
			return Redirect::route('/');
		} else if (Input::get('admin')) {
			Auth::logout();
			return Redirect::route('/administrator');
		}
		Auth::logout();
		return Redirect::route('/');
	}
	
	/**
	 * publikus bejelentkeztetés
	 * @TODO: ez nem marad igy, plane, hogy az eredeti oldalra lesz redirect
	 */
	public function postLogin() {
		$oldSession = Session::getId();
		$validator = Validator::make(Input::all(),
			array(
				'e' => 'required',
				'p' => 'required', 
			)
		);
		$error = array();
		if($validator->fails()) {
			$error[] = "Need to add email & password!";
			return array('error' => $error, 'msg' => "");
			#return Redirect::route('/')->withErrors($validator);
		} else {
			$auth = Auth::attempt(array(
				'email' => Input::get('e'),
				'password' => Input::get('p'), 
				'active' => 1,
                                
			),(Input::get('remember')==1 ? true : false));  //ha a remember checkbox be van pipálva, akkor vegye figyelembe a remember tokent   
                }
		
		if (!Auth::check()) {
			$error[] = "Invalid identification or password!";
			return array('error' => $error, 'msg' => "");
			#return Redirect::route('/login')->with('error', array('Hibás azonosító vagy jelszó!'));
		}
		
		if (!Auth::user()->confirmed) {
			$msg = "Need To confirmed Your email address!";
			return array('error' => '', 'msg' => $msg);
			#Auth::logout();
			#return Redirect::route('/login')->with('error', array('Az email cím még nincs hitelesítve!'));
		}
		#adott session-on belul a korabbi esemenyeket a bejelentkezo userhez kotjuk
		LogEvent::where('session_id', '=', $oldSession)->update(array("user_id" => Auth::user()->user_id));
		Ratings::where('session_id', '=', $oldSession)->update(array("user_id" => Auth::user()->user_id));
		 
                Cookie::make('user_brand', Input::get('e'), 60); // 1 óráig él a cookie   
		return array('error' => '', 'msg' => 'Authentication Success!');
		#return Redirect::route('/')->with('global', 'There was a problem logging you in.');
	}
	
	/**
	 * Admin Bejelentkeztetés
	 */
	public function postAdminLogin() {
		$validator = Validator::make(Input::all(),
		array(
		'email' => 'required',
		'password' => 'required'
		)
		);
		if($validator->fails()) {
			return Redirect::route('/administrator')->withErrors($validator);
		} else {
			$auth = Auth::attempt(array(
				'email' => Input::get('email'),
				'password' => Input::get('password'), 
				'active' => 1,
				'admin' => 1,
				'confirmed' => 1
			));
		}
		return Redirect::route('/administrator')->with('global', 'There was a problem logging you in.');
	}
	
	/**
	 * hogy args-ot children is elérje
	 */
	protected function setArgs($numargs, $arg_list) {
		$this->numargs = $numargs;
		$this->arg_list = $arg_list;
	}
	
	/**
	 * aktuális route beállítása
	 */
	protected function setActualRoutePath() {
		$this->actualRoutePath = Route::currentRouteName();
	}
	
	/**
	 * Aktuális route darabolása, változók elkérését segíti
	 */
	protected function setActualRouteElements() {
		if (!strlen($this->actualRoutePath)) {
			$this->setActualRoutePath();
		}
		$this->actualRouteElements = explode('/', $this->actualRoutePath);
	}
	
	
	protected function setActualLayoutName() {
		$this->needOver18 = $this->actualRouting->needover18;
                
                if (!Auth::check()) {
                    $cookie = Cookie::get('user_brand');
                    if ($cookie){
                        Auth::attempt(array(
				'email' => get('user_brand'), 
				'active' => 1,));
                    }
                    
                    #ha van, akkor auth && $_SESSION['over18'] = 1; #vagy valami 
                    if (false) {
                        Session::forget('over18');
                        Session::put('over18', 1);
                    }
                    
                }
                
		if ($this->actualRouting->need_auth && !Auth::check()) {#ha kell public auth
			#$this->actualRouting = null;
			if($this->actualRouting->routing_path != '/') {
				$this->actualRouting = Routing::where('routing_path', 'like', '/')->first();
				return Redirect::to('/');
			}
			#$this->actualRouting = Routing::where('routing_path', 'like', '/login')->first();
		} else if ($this->actualRouting->need_admin_auth && (!Auth::check() || !Auth::user()->admin)) {#ha kell admin auth
			$this->actualRouting = null;
			$this->actualRouting = Routing::where('routing_path', 'like', '/adminLogin')->first();
		} 
		$this->actualLayoutName = $this->actualRouting->layout_name;
	}
	
	/**
	 * route-hoz route object elkérése
	 */
	protected function setActualRouting() {
		if ($this->actualRouteElements == array()) {
			$this->setActualRouteElements();
		}
		$this->hasLang = false;
		if ($this->actualRouteElements[0] == '{lang}') {
			$this->hasLang = true;
			$this->actualRoutePath = str_replace("/{lang}", "", $this->actualRoutePath);
			if ($this->actualRoutePath == "" || $this->actualRoutePath == "/{lang}") {
				$this->actualRoutePath = "/";
			}
		}
		$this->actualRouting = Routing::where('routing_path', 'like', $this->actualRoutePath)->first();
		
		#@TODO: igy ma alakul
		$this->setActualLayoutName();
	}
	
	/**
	 * routeObject-hez CongtentObject-ek elkérése (render Sorrendben)
	 */
	protected function setContents() {
		if (!is_object($this->actualRouting)) {
			$this->setActualRouting();
		}
		if ($this->needOver18) {
			$this->styleCss["URL::asset('css/panic-button.css')"] = URL::asset('css/panic-button.css');
			$ret = $this->isOver18();
			if ($ret !== false) {
				return $ret;
			}
		} else {
			$this->styleCss["URL::asset('css/check-18.css')"] = URL::asset('css/check-18.css');
		}
		
		#ha admin feluleten vagyunk, akkor ugyanannak a modulnak az admin renderjere van szukseg
		/*if ($this->actualRouting->need_admin_auth) {
			$this->helperGetDataFunction = 'getAdminDatas';
		}*/
		
		$this->contents = Content::where('routing_id', $this->actualRouting->id)->where('active', 1)->orderBy('container_name')->orderBy('pos')->get();
		return true;
	}
	
	/**
	 * route-bol, használható adatok kinyerése
	 */
	protected function setUsableDatas() {
		$i = 0;
		
		foreach( $this->actualRouteElements as $are ) {
			if ( strpos($are, "{") === 0) {
				if ($i < $this->numargs) $this->usableDatas[$are] = $this->arg_list[$i];
				$i++;
			}
		}
	}
	
	/**
	 * admott contenthez adatok gyujtése view-nak
	 */
	protected function getHelperDataToContent($contentObject) {
		$helperDataRaw = json_decode($contentObject->helper_data_json);
		$helperData = array();
		if (strlen($contentObject->helper_data_json) != 0 || $this->hasLang) {
			if ($this->hasLang) {
				$helperDataRaw->lang ='{lang}'; #array('lang' => '{lang}');
			}
			
			foreach($helperDataRaw as $key => $value) {
				if (!is_array($value) && count($this->usableDatas) && isset($this->usableDatas[$value])) {
					$helperData[$key] = $this->usableDatas[$value];
				} else {
					$helperData[$key] = $value;
				}
			}
		}
		$helperData['helper_path'] = $contentObject->helper_path;
		if ($this->pagetitle) {
			$helperData['pagetitle'] = $this->pagetitle;
		}
		$helperData['metaDatas'] = $this->metaDatas;
		
		$helperData = array_merge($this->dataToOtherContent, $helperData);
		@require_once($contentObject->helper_class.".php");
		return call_user_func($contentObject->helper_class.'::'.$contentObject->helper_function, $helperData);	
	}
	
	/**
	 * Adat gyüjtése view-nak
	 */
	protected function setViewDatas() {
		foreach($this->contents as $c) {
			/*var_dump($c->modul);
			var_dump($c->pos);
			var_dump($c->helper_class);
			var_dump($c->helper_function);
			die();*/
			
			$return = $this->getHelperDataToContent($c);
			if ($return !== false) {#ha mégse kell renderelni
				if (isset($return['toOtherContent'])){
					$this->dataToOtherContent = array_merge($return['toOtherContent'], $this->dataToOtherContent);
				}
				
				$this->styleCss = array_merge($this->styleCss, $return['styleCss']);
				
				$this->jsLinks = array_merge($this->jsLinks, $return['jsLinks']);
				if (isset($return['scripts'])) {
					$this->scripts = array_merge($this->scripts, $return['scripts']);
				}
				
				if ($return['pagetitle'] && $this->pagetitle == "") {
					$this->pagetitle = $return['pagetitle'];
				}
				
				if ($return['metaDatas'] && $this->metaDatas == "") {
					$this->metaDatas = $return['metaDatas'];
				}
				
				$this->viewData[$c->container_name][$c->pos] = $return;
			}
		}
	}
	
	/**
	 * Set Data, to html Start
	 */
	protected function setHTMLStartDatas() {
		$this->viewData['styleCss'] = $this->styleCss;
		$this->viewData['jsLinks'] = $this->jsLinks;
		$this->viewData['scripts'] = $this->scripts;
		$this->viewData['pagetitle'] = $this->pagetitle;
		$this->viewData['metaDatas'] = $this->metaDatas;
	}
	
	/**
	 * header renderelesehez szukseges adatok osszeszedese
	 *
	 * @TODO: kérdés, hogy kell-e neki saját helper
	 */
	protected function setHeaderDatas() {
		if($this->actualRouting->routing_path == "/over18") {
			$this->viewData['headerView'] = "helper.header.over";
			$this->viewData['headerDatas']['isAuth'] = Auth::check();
			$this->viewData['headerDatas']['user'] = Auth::user();
		}else if ($this->actualRouting->adminheader) {#ekkor admin header
			$this->viewData['headerView'] = "helper.header.admin";
			$this->viewData['headerDatas']['isAuth'] = Auth::check();
			$this->viewData['headerDatas']['user'] = Auth::user();
		} else {#ekkor public header
			$menu = array();
			
			$menuObj = Mainmenu::where('active', '=', 1)->orderby('pos')->get();
			foreach($menuObj as $m) {
				$blank = "";
				if ($m['target'] == "_blank") {
					$blank = "target='_blank'";
				}
				$menu[] = array('show' => 1, 'a' => "<a ".$blank." href='".$m->href."' ".(strlen($m->onclick) > 0 ? "onclick='".$m->onclick."'" : "" ).">".$m->title."</a>", 'name' => $m->name);
			}
			if(Auth::check()) {
				$menu[] = array('show' => 1, 'a' => "<a href='/favoritevideos'>Favorite</a>", 'name' => "Favorite");
			}
			
			$this->viewData['headerDatas']['menuItems'] = $menu;
			#$this->viewData['headerDatas']['catgroup'] = TagGroup::getCategoriesToHeader();
			$this->viewData['headerDatas']['catgroup']['top'] = InternalTag::join('tag_groups', 'tag_groups.tag_group_id', '=', 'internal_tags.category_group')->where('internal_tags.active', '=', 1)->orderBy('internal_tags.pos')->take(12)->get(array('internal_tag_name', 'internal_tag_id'))->toArray();
			$this->viewData['headerDatas']['catgroup']['all'] = InternalTag::where('active', '=', 1)->orderBy('internal_tag_name')->get(array('internal_tag_name', 'internal_tag_id'))->toArray();
			$this->viewData['headerView'] = "helper.header.public";
			$this->viewData['headerDatas']['isAuth'] = Auth::check();
			$this->viewData['headerDatas']['user'] = Auth::user();
		
		}
	}
	
	/**
	 * footer renderelesehez szukseges adatok osszeszedese
	 *
	 * @TODO: kérdés like Header
	 */
	protected function setFooterDatas() {
		$this->viewData['footerView'] = "helper.footer.default";
		$this->viewData['footerDatas']['isAuth'] = Auth::check();
	}
	
	
	private function setEvents() {
	
		$eventTypeId = false;
		
		if (Cache::has('see_page_event_type_id')) {
			$eventTypeId = Cache::get('see_page_event_type_id');
		} else {
			$eventType = EventType::where('title', '=', 'see_page')->first();
			if ($eventType) {
				$eventTypeId = $eventType->event_type_id;
				Cache::forever('see_page_event_type_id', $eventTypeId);
			}
		}
		
		if ($eventTypeId) {
			$log = new LogEvent();
			$log->event_type_id = $eventTypeId;
			$log->session_id = Session::getId();
			$log->entity_name = "routings";
			$log->entity_id = $this->actualRouting->id;
			if (Auth::check()) {
				$log->user_id = Auth::user()->user_id;
			}
			$log->save();
		}
	
	}
	
	/**
	 * ide jön a vezérlés a route-bol
	 */
	public function showDefault() {
		$this->setArgs(func_num_args(), func_get_args());
		
		$contRet = $this->setContents();
		if ($contRet !== true) {
			return $contRet;
		}
		
		$this->actualRouting;
		if($this->actualRouting->routing_path == "/") {
			$this->pagetitle = "LiveRuby";
			$this->metaDatas = '<meta name="description" content="LiveRuby collects every adult content you might need." />
<meta name="keywords" content="porn, sex,free porn, ruby, porn videos, sex videos, free pussy, pussy, adult entertainment" />';
		} 
		
		
		
		
		if($this->actualRouting->routing_path == "/over18") {
			$this->styleCss["URL::asset('css/style.css')"] = URL::asset('css/style.css');
			$this->setEvents();	
		} else if($this->actualRouting->need_admin_auth == 0 && $this->actualRouting->adminheader == 0 && $this->actualRouting->system_route == 0) {
			$this->styleCss["URL::asset('css/style.css')"] = URL::asset('css/style.css');
			$this->jsLinks["URL::asset('js/header.js')"] = URL::asset('js/header.js');
			$this->setEvents();
		} else if($this->actualRouting->adminheader == 1 && $this->actualRouting->system_route == 1){
			$this->styleCss["URL::asset('css/admin.css')"] = URL::asset('css/admin.css');
		}
		
		$this->setUsableDatas();
		$this->setViewDatas();
		
		$this->setHeaderDatas();
		$this->setFooterDatas();
		
		$this->setHTMLStartDatas();
		#@TODO: ide ki kene talalni, hogy layout blade-et mi hatarozza meg, es hova mentjuk
		$this->layout->content = View::make('layout.'.$this->actualLayoutName, $this->viewData);
	}
}