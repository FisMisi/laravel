<?php

class EventHelper extends BaseController {

	public static function getAdminDatas($datas) {
		$datas['view'] = 'helper.admin.'.'event'.'.list';
		
		$datas['styleCss'] = array();
		
		$datas['jsLinks'] = array();
		$datas['helperDataJson'] = 'helperDataJson';
		
		$eventType = null;
		if (isset($_GET['et'])) {
			$eventType = $_GET['et'];
			$datas['helperData']['et'] = $_GET['et'];
		} else {
			$datas['helperData']['et'] = 0;
		}
		$datas['helperData']['etList'] = EventType::getEventTypeToList();
		
		
		$hasUser = null;
		if (isset($_GET['hu'])) {
			$hasUser = $_GET['hu'];
			$datas['helperData']['hu'] = $_GET['hu'];
		} else {
			$datas['helperData']['hu'] = 2;
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
		
		$idDatas = LogEvent::getDatasToAll($eventType, $hasUser, $limit, $page);
		$count = $idDatas['count'];
		$datas['helperData']['list'] = $idDatas['events'];
		$datas['helperData']['needPager'] = $count/$limit > 1 ? 1 : 0;
		$datas['helperData']['pagerOptions'] = ceil($count/$limit);
		return $datas;
	}
}