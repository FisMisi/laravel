<?php

require_once "ruby.interface.php" ;
require_once __DIR__."/../../models/ExternalCategory.php";
require_once __DIR__."/../../models/ExternalTag.php";
require_once __DIR__."/../../models/Video.php";
require_once __DIR__."/../../models/Star.php";
require_once __DIR__."/../../models/Partner.php";
require_once __DIR__."/../../models/VideosStars.php";
require_once __DIR__."/../../models/VideoSee.php";


class RedtubeInterface extends RubyInterface {

	public $baseUrl = "http://api.redtube.com/?data=redtube";
	public $method = "json";
	public $firstRun = true;
	
	public $partnerId;
	
	public function __construct() {
	
	}
	
	public function setMethod($method) {
		$this->method = in_array($method, array("json", "xml")) ? $method : $this->method;
	}
	
	protected function getCategories() {
		return file_get_contents($this->baseUrl.".Categories.getCategoriesList&output=".$this->method);
	}
	
	protected function getTags() {
		return file_get_contents($this->baseUrl.".Tags.getTagList&output=".$this->method);
	}
	protected function getStars() {
		return file_get_contents($this->baseUrl.".Stars.getStarList&output=".$this->method);
	}
	
	protected function getVideosToStars($star, $page) {
		return file_get_contents($this->baseUrl.".searchVideos&output=".$this->method."&stars[]=".$star."&page=".$page);
	}
	
	
	protected function getToDeletedVideos($page) {
		return file_get_contents($this->baseUrl.".Videos.getDeletedVideos&output=".$this->method."&page=".$page);
	}

	protected function getVideoByCategory($category, $page) {
		return file_get_contents($this->baseUrl."e.Videos.searchVideos&output=".$this->method."&page=".$page."&category=".$category['actualname']."&thumbsize=big");
	}
	
	protected function setCategoryData() {
		$categories = json_decode($this->getCategories());
		$categories = $categories->categories;
		$new = array();
		$deleted = array();
		$inArray = array();
		foreach ($categories as $category) {
			$categoryName = $category->category;
			if (!ExternalCategory::hasCategory($categoryName)) {
				$new[] = $categoryName;
			} else {
				$inArray[] = $categoryName;
			}
		}
		$deleted = ExternalCategory::getNotIn($inArray);
		if (count($deleted) || count($new)) {
			echo ":deleted category:";
			#$this->sendCategoryMail($new, $deleted);#meg kell irni
		}
	}
	
	/**
	 * @todo: Tag helyett ExternalTag
	 */
	protected function setTagData() {
		$tags = json_decode($this->getTags());
		$tags = $tags->tags;
		$new = array();
		$deleted = array();
		$inArray = array();
		foreach($tags as $tag) {
			$tagName = $tag->tag->tag_name;
			$tagName = ExternalTag::reformatTag($tagName);
			if (!ExternalTag::hasTag($tagName)) {
				$new[] = $tagName;
				$etObj = new ExternalTag();
				$etObj->external_tag_name = $tagName;
				$etObj->partner_id = $this->partnerId;
				$etObj->save();
			}
			$inArray[] = $tagName;
		}
		$deleted = ExternalTag::getNotIn($inArray, $this->partnerId);
		if (count($new) || count($deleted)) {
			#$this->sendTagMail($new, $deleted);#meg kell irni
		}
	}
	
	protected function getPosToCategory($category) {
		$ret = array();
		if ($this->firstRun) {#ekkor az utolso page, utolso eleme kell
			$this->setMethod('xml');
			$xmlBase = $this->getVideoByCategory($category, 1);
			$xmlObj=simplexml_load_string($xmlBase);
			$count = $xmlObj->count;
			$lastPageLastItem = $count+20-ceil($count/20)*20;
			$this->setMethod('json');
			return array('page' => ceil($count/20), 'pos' => $lastPageLastItem-1);
		} else {#ekkor lapozgatni kell
			$iterator = true;
			$page = 0;
			while ($iterator) {
				$page++;
				$tempVideos = json_decode($this->getVideoByCategory($category, $page));
				$tempVideos = $tempVideos->videos;
				$pos = 0;
				foreach($tempVideos as $tv) {
					$pos++;
					$baseVideoId = $tv->video->video_id;
					$active = Video::isActiveByBaseVideoId($baseVideoId, $this->partnerId);
					if (!is_null($active)) {
						if ($pos == 1) {
							$page = $page-1;
							$pos = 21;
						}
						return array('page' => $page, 'pos' => $pos-2);
					}
				}
			}
		}
		
		return array("page" => $page, 'pos' => $pos);
	}
	
	public function getByCatPageRow($category, $page, $row) {
		var_dump($category);
		var_dump($page);
		var_dump($row);
		for($i = $page; $i > 0;$i--) {
		#asdasdas
			$tempVideos = json_decode($this->getVideoByCategory($category, $i));
			$tempVideos = $tempVideos->videos;
			for($j = $pos;$j > -1;$j--) {
				$tv = $tempVideos[$j];
				$baseVideoId = $tv->video->video_id;
				$active = Video::isActiveByBaseVideoId($baseVideoId, $this->partnerId);
				if (is_null($active)) {#ez csak hibaszures miatt
					$video = new VideoN();
						
					$video->base_video_id = $tv->video->video_id;
					$video->video_name = $tv->video->title;
					#@todo: tagname cache-ből, hogy csak 1* kelljen formazni
					$seoRet = Video::getSeoNameToTitle($tv->video->title, $tv->video->tags[0]->tag_name, $this->partnerId, $video->base_video_id);
					$video->video_seo_name = $seoRet['seoName'];
					$video->valid_seo = $seoRet['validSeo'];
					
					$video->default_thumb = $tv->video->thumbs[0]->src;
					
					$duration = $tv->video->duration;
					$darray = explode(":", $duration);
					$d = "";
					$dd = "";
					$ddd = ":";
					for($k = count($darray);$k > 0;$k--) {
						if (strlen($darray[$k-1]) == 1) {
							$idD = "0".$darray[$k-1];
						} else {
							$idD = $darray[$k-1];
						}
						$d = $idD.$dd.$d;
						$dd = $ddd;
					}
					if (count($darray) == 1) {
						$d = '00:00:'.$d;
					} else if (count($darray) == 2) {
						$d = '00:'.$d;
					}
					
					$video->length = $d;
					$video->partner_id = $this->partnerId;
					$video->video_flash_link = VideoN::getFlashLinkToToBaseVideoIdRt($video->base_video_id);
					$video->rating = $tv->video->rating;
					$video->rating_number = $tv->video->ratings;
					$video->sum_rating = round($tv->video->rating*$tv->video->ratings);
					$video->save();
					
					$vs = new VideoSee();
					$vs->video_id = $video->video_id;
					$vs->see_count = $tv->video->views;
					$vs->timestamps = false;
					$vs->save();
					
					$video->setTags($tv->video->tags);
					$video->generateThumbsDirectoryRT($tv->video->thumbs);
				
				}
			}
			$pos = 19;
		}
	
	}
	
	
	protected function getNewVideos() {
		
		$videoCountToRT = Video::where('partner_id', '=', $this->partnerId)->count();
		
		if ($videoCountToRT) {
			$this->firstRun = false;
		} else {
			$this->firstRun = true;
		}
		
		$categories = ExternalCategory::getActiveCategoryNames();
		$systemUserId = 0;
		foreach($categories as $category) {
			#asasdasd
			#$_SESSION['categoriesFromImport'][$category['actualname']]['category'] = $category['actualname'];
			$cIterators = $this->getPosToCategory($category);
			$page = $cIterators['page'];
			$pos = $cIterators['pos'];
			#$_SESSION['categoriesFromImport'][$category['actualname']]['page'] = $page;
			#$_SESSION['categoriesFromImport'][$category['actualname']]['pos'] = $pos;
			/*
				Visszakaptuk, hogy melyik oldal, melyik poziciójától kell visszafele hamadnunk!
				Az elso (utolso) oldal után a pos visszaáll 20-ra(19re)
			*/
			
			for($i = $page; $i > 0;$i--) {
			#asdasdas
				$tempVideos = json_decode($this->getVideoByCategory($category, $i));
				$tempVideos = $tempVideos->videos;
				for($j = $pos;$j > -1;$j--) {
					$tv = $tempVideos[$j];
					$baseVideoId = $tv->video->video_id;
					$active = Video::isActiveByBaseVideoId($baseVideoId, $this->partnerId);
					if (is_null($active)) {#ez csak hibaszures miatt
						$video = new Video();
							
						$video->base_video_id = $tv->video->video_id;
						$video->video_name = $tv->video->title;
						#@todo: tagname cache-ből, hogy csak 1* kelljen formazni
						$seoRet = Video::getSeoNameToTitle($tv->video->title, $tv->video->tags[0]->tag_name, $this->partnerId, $video->base_video_id);
						$video->video_seo_name = $seoRet['seoName'];
						$video->valid_seo = $seoRet['validSeo'];
						
						$video->default_thumb = $tv->video->thumbs[0]->src;
						
						$duration = $tv->video->duration;
						$darray = explode(":", $duration);
						$d = "";
						$dd = "";
						$ddd = ":";
						for($k = count($darray);$k > 0;$k--) {
							if (strlen($darray[$k-1]) == 1) {
								$idD = "0".$darray[$k-1];
							} else {
								$idD = $darray[$k-1];
							}
							$d = $idD.$dd.$d;
							$dd = $ddd;
						}
						if (count($darray) == 1) {
							$d = '00:00:'.$d;
						} else if (count($darray) == 2) {
							$d = '00:'.$d;
						}
						
						$video->length = $d;
						$video->partner_id = $this->partnerId;
						$video->video_flash_link = VideoN::getFlashLinkToToBaseVideoIdRt($video->base_video_id);
						$video->rating = $tv->video->rating;
						$video->rating_number = $tv->video->ratings;
						$video->sum_rating = round($tv->video->rating*$tv->video->ratings);
						$video->save();
						
						$vs = new VideoSee();
						$vs->video_id = $video->video_id;
						$vs->see_count = $tv->video->views;
						$vs->timestamps = false;
						$vs->save();
						
						$video->setTags($tv->video->tags);
						$video->generateThumbsDirectoryRT($tv->video->thumbs);
					
					}
				}
				$pos = 19;
			}
		}
	}
	
	public function getDeletedVideos() {
		DB::disableQueryLog();
		$iterator = true;
		$systemUserId = 0;
		$i = 0;
		while($iterator) {
			$i++;
			$tempVideos = json_decode($this->getToDeletedVideos($i));
			$tempVideos = $tempVideos->videos;
			foreach($tempVideos as $tv) {
				$baseVideoId = $tv->video->video_id;
				$active = Video::isActiveByBaseVideoId($baseVideoId, $this->partnerId);
				if ($active) {
					Video::setInactiveVideoByBaseVideoId($baseVideoId, $systemUserId, "removedVideo", $this->partnerId);
				} else {
					if ($active === 0) {
						$iterator = false;
					}
				}
			}
		}
	}
	
	
	public function setStars() {
		$stars = json_decode($this->getStars());
		foreach($stars->stars as $star) {
			$starName = $star->star->star_name;
			$starName = ucwords(strtolower($starName));
			$starObj = Star::where("star_name", "=", $starName)->first();
			if (is_null($starObj)) {
				$starObj = new Star();
				$starObj->star_name = $starName;
				$starObj->save();
			}
			
			$needMore = true;
			$i = 0;
			while($needMore) {
				$i++;
				$videos = json_decode($this->getVideosToStars($starName, $i));
				if (!isset($videos->code)) {
					foreach($videos->videos as $video) {
						$baseVideoId = $video->video->video_id;
						$videoId = reset(VideoN::where("base_video_id", $baseVideoId)->where('partner_id', '=', $this->partnerId)->get(array('video_id'))->first()->toArray());
						$id = VideosStarsN::where('video_id', $videoId)->where("star_id", $starObj->star_id)->first();
						if (is_null($id)) {
							$new = new VideosStarsN();
							$new->video_id = $videoId;
							$new->star_id = $starObj->star_id;
						} else {
							$needMore = false;
						}
					}
				}
			}
		}
	}
	
	public function fullRun() {
	DB::disableQueryLog();
		var_dump("rtfut");
		die();
		$this->partnerId = Partner::getIdToName('Redtube');
		$this->setMethod("json");
		$this->setCategoryData();
		$this->setTagData();
		$this->getNewVideos();
		$this->getDeletedVideos();
		if (true) {
			$this->setStars();
		}
		
	}
	
	public function getRenegeratedVideos() {
		$video_id = Input::get('video_id');
		$rawDatas = file_get_contents('livechannel.porta.hu/getRegeneratedDatas?video_id='.$video_id);
		$datas = json_decode($rawDatas);
		Video::setVideoDataFromRegenerate($datas);
	}
	
	#eles szervertol data kerese
	public function saveRegenerateVideoDatas0() {
		$rawDatas = file_get_contents('http://liveruby.com/getregdatart');
		$datas = json_decode($rawDatas);
		var_dump($datas[0]);
		foreach($datas as $data) {
			$tmpVideo = new TmpVideos();
			$tmpVideo->video_id = $data->video_id;
			$tmpVideo->base_video_id = $data->base_video_id;
			$tmpVideo->save();
			var_dump($tmpVideo->video_id);
		}
		var_dump(count($datas));
	}
	
	#elkert adatok teszten feltoltes
	public function saveRegenerateVideoDatas1() {
	
		$limit = 1000;
		for($i=0;$i<5;$i++) {
		$datas = TmpVideos::getState0($limit);
			foreach($datas as $data) {
				$isvalid = Video::isValidFromRedtube($data['base_video_id']);
				$tmpVideo = TmpVideos::find($data['video_id']);
				if ($isvalid === 0) {
					#ilyen nincs
				} else if ($isvalid === 2) {
					#ekkor se kell sok minden
					$tmpVideo->state = 3;
					
				} else {
					#$tmpDatas = Video::getThumbsToBaseId($data['base_video_id']);
					$tmpVideo->default_thumb = $isvalid['defaultThumbs'];
					$tmpVideo->thumbs = json_encode($isvalid['thumbs']);
					$tmpVideo->state = 1;
				}
				$tmpVideo->save();
			}
			var_dump($i);
			var_dump(count($datas));
			echo "<br/>__________________________________<br/>";
		}
	}
	
	#teszt valaszol eles torolt keresere
	public function sendRemoveDatas() {
		$datas = TmpVideos::where('state', '=', 3)->get(array('video_id'))->toArray();
		
		for($i=0;$i<1000;$i++) {
		$data = $datas[$i];
		#foreach($datas as $data) {
			$retData = file_get_contents('http://liveruby.com/getdelvid?v='.$data['video_id']);
			if ($retData == 1) {
				$video = TmpVideos::find($data['video_id']);
				$video->state = 2;
				$video->save();
			}
		}
	}
	
	public function sendRefactorDatas() {
		$datas = TmpVideos::where('state', '=', 1)->take(1000)->get(array('video_id', 'thumbs', 'default_thumb'))->toArray();
		foreach($datas as $data) {
			$retData = file_get_contents('http://liveruby.com/getrefvid?v='.$data['video_id']."&d=".$data['default_thumb']."&t=".$data['thumbs']);
			if ($retData == 1) {
				$video = TmpVideos::find($data['video_id']);
				$video->state = 2;
				$video->save();
			}
		}
	}
	
	public function getRemoveDatas() {
		$all = Input::all();
		$videoId = $all['v'];
		$video = Video::find($videoId);
		$video->inactivation_time = date("Y-m-d H:i:s");
		$video->inactivation_user_id = 0;
		$video->inactivation_reason = "Removet from API";
		$video->active2 = 0;
		$video->save();
		echo 1;
		return;
	}
	
	public function getRefactorDatas() {
		$all = Input::all();
		$videoId = $all['v'];
		$defaultThumb = $all['d'];
		$thumbs = json_decode($all['t']);
		$video = Video::find($videoId);
		$video->generateThumbsDirectory($thumbs);
		$video->default_thumb = $defaultThumb;
		$video->save();
		echo 1;
		return;
	}
	
	#teszt valaszol eles valid keresere
	public function sendValidVideoIds() {
		$datas = TmpVideos::where('state', '=', 1)->get(array('video_id'))->toArray();
		echo json_encode($datas);
	}
	
	public function sendValidVideoDatas() {
		$id = Input::get('video_id');
		$datas;
	}
	
	public function echoRegenerateVideosToJSON() {
		$datas = Video::getVideosToRegenerate();
		echo json_encode($datas);
	}
	
	
	public function regenerateThumbs() {
		Video::regenerateThumbs();
	}
	
        
        public function newApiGetDeletedCount() {
            $deleteUrl = "http://api.redtube.com/?data=redtube.Videos.getDeletedVideos&output=json";
            $jsonDatas = file_get_contents($deleteUrl);
            $datas = json_decode($jsonDatas);
            return $datas->count;
        }
        
        public function newApiGetRemoveBaseIdsToPage($page) {
            if (is_null($page)) {
            $page = 1;
        }

        $deleteUrl = "http://api.redtube.com/?data=redtube.Videos.getDeletedVideos&output=json&page=".$page;
            $jsonDatas = file_get_contents($deleteUrl);
            $datas = json_decode($jsonDatas);
            $ret = array();
            foreach($datas->videos as $video) {
                $ret[] = $video->video->video_id;
            }
            return $ret;
        }
        
        public function newApiRemoveVideos2() {
            $count = $this->newApiGetDeletedCount();
            $maxPage = ceil($count/20);
            for ($i = $maxPage;$i>0;$i--) {
                $baseIds = $this->newApiGetRemoveBaseIdsToPage($i);
                foreach($baseIds as $baseId) {
                    $video = TmpVideos::where('base_video_id', '=', $baseId)->get();
                    if(count($video) == 1) {
                        echo $video[0]->base_video_id."<br/>";
                        $video[0]->state = 3;
                        $video[0]->save();
                    } else {
                        echo "ERROR BASE_VIDEO_IS:".$baseId."<br/>";
                    }
                }
            }
        }
        
        public function newApiRemoveVideos() {
            $count = $this->newApiGetDeletedCount();
            $maxPage = ceil($count/20);
            for ($i = $maxPage;$i>0;$i--) {
                $baseIds = $this->newApiGetRemoveBaseIdsToPage($i);
                foreach($baseIds as $baseId) {
                    $video = Video::where('base_video_id', '=', $baseId)->where('partner_id', '=', 1)->get();
                    if(count($video) == 1) {
                        echo $video[0]->base_video_id."<br/>";
                        $video[0]->active2 = 0;
                        $video[0]->inactivation_reason = "remove by New Api";
                        $video[0]->inactivation_time = date('Y-m-d H:i:s');
                        $video[0]->inactivation_user_id = 0;
                        $video[0]->save();
                    } else {
                        echo "ERROR BASE_VIDEO_IS:".$baseId."<br/>";
                    }
                }
            }   
        }
        
        public function newApiGetImportCount() {
            $url = "http://api.redtube.com/?data=redtube.Videos.searchVideos&output=json";
            $jsonDatas = file_get_contents($url);
            $datas = json_decode($jsonDatas);
            return $datas->count;
        }
        
        public function newApiGetImportDatasToPage($page = 1) {
            $url = "http://api.redtube.com/?data=redtube.Videos.searchVideos&output=json&thumbsize=big&page=".$page;
            $jsonDatas = file_get_contents($url);
            $datas = json_decode($jsonDatas);
            return $datas->videos;
        }
        
        public static function properLengthFormat($rawLength) {
            $tmpArray = explode(":", $rawLength);
            $tmpCount = count($tmpArray);
            switch ($tmpCount) {
            case 0:
                return "00:00:00";
                break;
            case 1:
                if(strlen($tmpArray[0]) == 1) return "00:00:0".$rawLength;
                return "00:00:".$rawLength;
                break;
            case 2:
                if(strlen($tmpArray[0]) == 1) return "00:0".$rawLength;
                return "00:".$rawLength;
                break;
            case 3:
                if(strlen($tmpArray[0]) == 1) return "0".$rawLength;
                return $rawLength;
                break;
            default:
                return $rawLength;
            }
        }
        
        public function newApiImportVideo($rawVideo) {
            $video = new Video();
            $video->length = self::properLengthFormat($rawVideo->video->duration);
            $video->base_video_id = $rawVideo->video->video_id;
            $video->partner_id = 1;
            $video->video_name = $rawVideo->video->title;
            $seoRet = Video::getSeoNameToTitle($rawVideo->video->title, $rawVideo->video->tags[0]->tag_name, 1, $video->base_video_id);
            $video->video_seo_name = $seoRet['seoName'];
            $video->valid_seo = $seoRet['validSeo'];
            $video->default_thumb = $rawVideo->video->thumbs[0]->src;
            $video->video_flash_link = Video::getFlashLinkToToBaseVideoIdRt($video->base_video_id);
            $video->rating = $rawVideo->video->rating;
            $video->rating_number = $rawVideo->video->ratings;
             $video->sum_rating = round($video->rating*$video->rating_number);
            $video->save();
            
            $vs = new VideoSee();
            $vs->video_id = $video->video_id;
            $vs->see_count = $rawVideo->video->views;
            $vs->timestamps = false;
            $vs->save();
            
            $video->setTags($rawVideo->video->tags);
            $video->generateThumbsDirectoryRT($rawVideo->video->thumbs);
        }
        
        public function newApiFullImport() {
            $count = $this->newApiGetImportCount();
            $limit = 20;
            $maxPage = intval(ceil($count/$limit));
            $j = 0;
            for($i = $maxPage;$i > 0;$i--) {
                $videoDatas = $this->newApiGetImportDatasToPage($i);
                $j++;
                if($j == 100) {
                    echo $i."<br/>";
                    $j = 0;
                }
                foreach($videoDatas as $rawVideo) {
                    $tmpQuery = Video::where('partner_id', '=', 1);
                    $tmpQuery->where('video_id', '=', $rawVideo->video->video_id);
                    $tmpVideo = $tmpQuery->count();
                    if (is_null($tmpVideo) || $tmpVideo < 1 || $tmpVideo === false) {
                        #ekkor kell import
                        $this->newApiImportVideo($rawVideo);
                    }
                }
            }
        }
}