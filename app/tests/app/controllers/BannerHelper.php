<?php

class BannerHelper extends BaseController {

	public static function getViewDatas($datas) {
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		$datas['view'] = 'helper.banner.default';
		$datas['helperDataJson'] = 'helperDataJson';
		$datas['helperData']['banner'] = BannerType::where('name', 'like', $datas['name'])->first();
		return $datas;
	}
	
	public static function modify() {
		if (
			(Input::get('picture_src') === '' && 
			Input::get('flash_src') === '' && 
			Input::get('iframe_src') === '' ) ||
			Input::get('link') === '' || 
			Input::get('title') === '' || 
			Input::get('name') === ''
		) {
			return Redirect::to('administrator/banners/'.Input::get('banner_type_id'));
		}
		
		if (Input::get('banner_type_id') == '0' || is_null(Input::get('banner_type_id'))) {
			$banner = new BannerType;
		} else {
			$banner = BannerType::find(Input::get('banner_type_id'));
			if (is_null($banner)) {
				return Redirect::to('administrator/banners/'.Input::get('banner_type_id'));
			}
		}
		
		$banner->name = Input::get('name');
		$banner->title = Input::get('title');
		$banner->link = Input::get('link');
		
		$banner->picture_src = Input::get('picture_src');
		$banner->flash_src = Input::get('flash_src');
		$banner->iframe_src = Input::get('iframe_src');
		
		$banner->flashvars = Input::get('flashvars');
		
		$banner->active = is_null(Input::get('active'))? 0 : 1;
		
		$banner->save();
		return Redirect::to('administrator/banners/'.$banner->banner_type_id);
		
	}
	
	public static function subFunc($datas) {
		$datas['helperData']['banner'] = BannerType::find($datas['id']);
		
		$datas['view'] = 'helper.admin.'.'banner'.'.modify';
		if (!isset($datas['pagetitle'])) {
			$datas['pagetitle'] = 'Banner szerkesztése';
		}
		return $datas;
	}
	
	public static function getAdminDatas($datas) {
		$datas['view'] = 'helper.admin.'.'banner'.'.list';
		
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		$datas['helperDataJson'] = 'helperDataJson';
		if (isset($datas['id'])) {
			return self::subFunc($datas);
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
			$datas['helperData']['limit'] = 20;
		}
		
		$page = 1;
		if(isset($_GET['page'])) {
			$datas['helperData']['page'] = $_GET['page'];
			$page = $_GET['page'];
		} else {
			$datas['helperData']['page'] = 1;
		}
		$idDatas = BannerType::getDatasToAll($active);
		$count = $idDatas['count'];
		$banners = $idDatas['banners'];
		$datas['helperData']['needPager'] = $count/$limit > 1 ? 1 : 0;
		$datas['helperData']['pagerOptions'] = ceil($count/$limit);
		$datas['helperData']['list'] = $banners;
		return $datas;
	}
}