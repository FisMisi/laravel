<?php
@require_once __DIR__.'/interfaces/youporn.interface.php';
@require_once __DIR__.'/interfaces/pornhub.interface.php';
require __DIR__.'/interfaces/redtube.interface.php';
class InterfaceHelper extends BaseController {

	public static function youpornInterface() {
		$yp = new YoupornInterface();
		/*$count = VideoN::where('partner_id', '=', 3)->count();
		
		$pageLimit = 20000;
		$page = ceil($count/$pageLimit);
		for($i = 0;$i<$page;$i++) {
			$query1 = VideoN::where('partner_id', '=', 3);
			echo $pageLimit."|".$i."<br/>";
			$query1->take($pageLimit)->skip($pageLimit*$i)->groupBy('base_video_id')->havingRaw('count(*) > 1');
			$videosN = $query1->selectRaw('distinct(base_video_id) as base_video_id ,min(video_id) as min_video, max(video_id) as max_video, count(*)')->get()->toArray();
			var_dump(count($videosN));
			#$videosN = VideoN::where('partner_id', '=', 3)->take($pageLimit)->skip($pageLimit*$i)->get(array('base_video_id', 'video_id'));
			foreach($videosN as $vn) {    
				if ($vn['max_video'] != $vn['min_video']) {
					VideoN::where('video_id', '=', $vn['max_video'])->delete();
				}
			}
			
		}
		
		return 'alma';*/
		#var_dump('youporn');
		#die();
		$yp->fullRun();
	}
	
	
	public static function pornhubeInterface() {
		$ph = new PornhubInterface();
		$ph->fullRun();
	}

	public static function redtubeInterface() {
		#$category = $_GET['category'];
		#$page = $_GET['page'];
		#$row = $_GET['row'];
		
		
		$rt = new RedtubeInterface();
		$rt->partnerId = 1;
		$rt->setMethod("json");
		$rt->getDeletedVideos();
		#$rt->getByCatPageRow($category, $page, $row);
		
		#var_dump('rt');
		#die();
		#$rt->fullRun();
		return 'alma';
	}
	
	public static function saveRegenerateVideoDatas0() {
		$ret = new RedtubeInterface();
		$ret->saveRegenerateVideoDatas0();
	}
	
	public static function regenerateThumbsTeszt() {
		$ret = new RedtubeInterface();
		$ret->saveRegenerateVideoDatas1();
	}
	
	public static function getDataToRegenerate() {
		$ret = new RedtubeInterface();
		$ret->echoRegenerateVideosToJSON();
	}
	
	public static function postDeletedVideoIds() {
		$ret = new RedtubeInterface();
		$ret->sendRemoveDatas();
	}
	
	public static function getDeletedVideoIds() {
		$ret = new RedtubeInterface();
		$ret->getRemoveDatas();
	}
	
	public static function postRefaktVideoIds() {
		$ret = new RedtubeInterface();
		$ret->sendRefactorDatas();
	}
	
	public static function getRefaktVideoIds() {
		$ret = new RedtubeInterface();
		$ret->getRefactorDatas();
	}
	
	public static function regenerateRedtubeThumbs() {
		$ret = new RedtubeInterface();
		$ret->regenerateThumbs();
	}
        
        
        public static function newApiRedtubeRemoveVideo() {
            $interface = new RedtubeInterface();
            $interface->newApiRemoveVideos2();
        }
        
        public static function newApiPornhubRemoveVideo() {
            $interface = new PornhubInterface;
            $interface->newApiRemoveVideos();
        }
        
        public static function newApiYoupornRemoveVideo() {
            $interface = new YoupornInterface;
            $interface->newApiRemoveVideos();
            
        }
        
        public static function getActive2VideosFromFront() {
            DB::disableQueryLog();
            #$countJson = file_get_contents("http://liveruby.com/getFrontDatasCount");
            $count = 1866702;
            $limit = 10000;
            $maxPage = intval(ceil($count/10000));
            var_dump($maxPage);
            
            #$i = $_GET['page']; 
            for($i = 0;$i<$maxPage;$i++) {
                $frontJson = file_get_contents("http://liveruby.com/getFrontDatas?page=".$i);
                $front = json_decode($frontJson);
                
                foreach($front as $f) {
                    $tq = Tmp2Videos::where('base_video_id', '=', $f->base_video_id);
                    $t = $tq->where('partner_id', '=', $f->partner_id)->count();
                    if (is_null($t) || $t < 1 || $t === false) {
                        $tmp2Videos = new Tmp2Videos();
                        $tmp2Videos->base_video_id = $f->base_video_id;
                        $tmp2Videos->partner_id = $f->partner_id;
                        $tmp2Videos->active2 = 1;
                        $tmp2Videos->save();
                    }
                }
            }
        }
        
        public static function getFrontDatasCount() {
            return json_encode(Tmp2Videos::where('active2', '=', 1)->count());
        }
        
        public static function getFrontDatas() {
            $page = 0;
            $baseSkip = 350000;
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            }
            $ret = Tmp2Videos::where('active2', '=', 1)->take(10000)->skip(10000*$page+$baseSkip)->orderBy('id')->get(array('base_video_id', 'partner_id'))->toArray();
            return json_encode($ret);
        }
        
        public static function getValidActive2Local() {
            $count = 1870631;
            $limit = 10000;
            DB::disableQueryLog();
            $maxPage = intval(ceil($count/$limit));
            for($i = 0;$i < $maxPage;$i++) {
                $skip = $limit*$i;
                $query = Video::where('active2', '=', 1);
                $query->take($limit)->skip($skip);
                $tt = $query->orderBy('video_id')->get(array('base_video_id', 'partner_id'))->toArray();
                foreach($tt as $t) {
                    $tmpQuery = Tmp2Videos::where('base_video_id', '=', $t['base_video_id']);
                    $tmpQuery->where('partner_id', '=', $t['partner_id']);
                    $tmpVideo = $tmpQuery->get(array('id'))->toArray();
                    
                    if (is_null($tmpVideo) || $tmpVideo === false || count($tmpVideo) < 1) {
                        $tmp2Video = new Tmp2Videos();
                        $tmp2Video->base_video_id = $t['base_video_id'];
                        $tmp2Video->partner_id =$t['partner_id'];
                        $tmp2Video->active2 = 1;
                        $tmp2Video->save();
                    }
                    
                }
            }
        }
        
        public static function sendRemovedDatas() {
            $count = Tmp2Videos::where('active2', '=', 0)->count();
            $limit = 1000;
            $maxPage = ceil($count/$limit);
            for($i=0;$i<$maxPage;$i++) {
                $sendDatas = Tmp2Videos::where('active2', '=', 0)->take($limit)->skip($limit*$i)->get(array('base_video_id', 'partner_id'))->toArray();
                $sendJson = json_encode($sendDatas);
                file_get_contents("http://liveruby.com/removeFrontNewApi?d=".$sendJson);
            }
        }
        
        public static function removeFrontNewApi() {
            
            if(!isset($_GET['d'])) {
                return false;
            }
            
            $datas = json_decode($_GET['d']);
            foreach($datas as $data) {
                $rem = Video::where('base_video_id', '=', $data['base_video_id'])->where('partner_id', '=', $data['partner_id'])->first();
                $rem->active2 = 0;
                $rem->inactivation_time = date('Y-m-d H:i:s');
                $rem->inactivation_user_id = 0;
                $rem->inactivation_reason = "remove by New Api";
                $rem->save();
            }
            return true;
        }
}