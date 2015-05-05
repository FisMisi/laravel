<?php

@require_once("ruby.interface.php");
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

class YoupornInterface extends RubyInterface {

	protected $partnerId;
	protected $sourceBase = "/../../../interfacedump/";
	protected $sourceFile = "YouPorn-Embed-Videos-Dump.csv";
	protected $sourceDeleteFile = "deleted_videos.csv";
	protected $iframeBase = "http://www.youporn.com/embed/";

	protected $actualNewLine = 1;
	protected $actualDeletedLine = 1;
	protected $maxLinePerConnection = 5000;

	protected function getVideoBaseIdFromIframe($iframe) {
		$posStart = strpos($iframe, "'");
		$ret = substr($iframe, $posStart+1);
		$posEnd1 = strpos($ret, "'");
		$ret = substr($ret, 0, $posEnd1);
		$ret = str_replace($this->iframeBase, "", $ret);
		$posEnd = strpos($ret, "/");
		$ret = substr($ret, 0, $posEnd);
		return $ret;
	}

	protected function setByLine($line) {
		#return true;
		list($iframe, $thumbs, $title, $tags, $categories, $stars, $time) = explode("|", $line);
		$iframe = str_replace('"', "", $iframe);
		$iframeEndPos = strpos($iframe, ">");
		$iframe = substr($iframe, 0, $iframeEndPos+1);
		$iframe.= "</iframe>";
		$videoBaseId = $this->getVideoBaseIdFromIframe($iframe);
		$hasVideo = Video::hasBaseVideoId($videoBaseId, $this->partnerId);
		if ($hasVideo) return false;#ekkor nem kell feldolgozni
		
		$tagArray = explode(",", $categories);
		if (!is_array($tagArray) || count($tagArray) == 0) {
			$tagArray[0] = 'video';
		}
		$thumbsArray = explode(",", $thumbs);
		
		$video = new Video();
		$video->base_video_id = $videoBaseId;
		$video->video_name = str_replace('"', '', $title);
		$seoRet = Video::getSeoNameToTitle($title, $tagArray[0], $this->partnerId, $video->base_video_id);
		$video->video_seo_name = $seoRet['seoName'];
		$video->valid_seo = $seoRet['validSeo'];#DONE
		
		$video->default_thumb = $thumbsArray[0];
		if (strlen($time) == 2 && strpos($time, 's') == 1) {
			$time= '0'.$time;
		}
		if(strpos($time,'m') === false){
			$time='00m'.$time;
		} 
		if(strpos($time,'h') === false){
			$time='00h'.$time;
		}else {
			$tpos = strpos($time, 'h');
			
			$tf = substr($time, 0, $tpos);
			$tfint = (integer)$tf;
			if  ($tfint > 23) {
				$time = "01h01m01s";
				
			}
			unset($tpos);
			unset($tf);
			unset($tfint);
		}
		$time = str_replace("h", ":", $time);
		$time = str_replace("m", ":", $time);
		$time = str_replace("s", "", $time);
		$video->length = $time;#Done
		
		$video->video_flash_link = $iframe;
		$video->partner_id = $this->partnerId;
		$video->save();
		$sv = new VideoSee();
		$sv->video_id = $video->video_id;
		$sv->see_count = 0;
		$sv->see_count_l = 0;
		$sv->timestamps = false;
		$sv->save();
		$video->setTagsFromArray($tagArray, $this->partnerId);#DONE
		$video->generateThumbsDirectory($thumbsArray);#Done
		$starArray = explode(",", $stars);
		foreach($starArray as $starName) {
			$starName = str_replace('"', '', $starName);
			$starName = trim($starName);
			$starName = ucwords(strtolower($starName));
			if ($starName != "") {
				$starObj = Star::where("star_name", "=", $starName)->first();
				if (is_null($starObj)) {
					$starObj = new Star();
					$starObj->star_name = $starName;
					$starObj->save();
				}
				$vsObj = new VideosStars();
				$vsObj->video_id = $video->video_id;
				$vsObj->star_id = $starObj->star_id;
				$vsObj->save();
				unset($vsObj);
				unset($starObj);
			}
		}
		unset($video);
		unset($starArray);
		unset($time);
		unset($thumbsArray);
		unset($thumbs);
		unset($tagArray);
		unset($categories);
		unset($hasVideo);
		unset($videoBaseId);
		unset($iframe);
		unset($title);
		unset($tags);
		unset($stars);
		unset($line);
	}
	
	
	protected function setDeletedByLine($line) {
		$baseId = substr($line, 22);
		$endPos = strpos($baseId, "/");
		$baseId = substr($baseId, 0, $endPos);
		Video::setInactiveVideoByBaseVideoId($baseId, 0, "removedVideo", $this->partnerId);
	}
	
	protected function setDeleted() {
		DB::reconnect();
		$source = __DIR__.$this->sourceBase.date("Ymd")."/".$this->sourceDeleteFile;
		$handle = fopen($source, 'r');
		while(!feof($handle)) {
			$line = fgets($handle); 
			if ($line != false) {
				if ($this->actualDeletedLine == 5000) {
					$this->actualDeletedLine = 0;
					DB::reconnect();
				}
				$this->setDeletedByLine($line);
				$this->actualDeletedLine++;
			}
		}
	}
	
	public static function fullRunS() {
		$yp = new self();
		$yp->fullRun();
	}
	
	public function fullRun() {
		#var_dump("fut");
		#die();
		DB::disableQueryLog();
		#$ret = mail("paronai.tamas@ikron.hu", "teszt", "Ez egy teszt üzenet");
		#$ret = mail("paronai.tamas@gmail.com", "teszt", "Ez egy teszt üzenet");
		$this->partnerId = Partner::getIdToName('Youporn');
		$source = __DIR__.$this->sourceBase.date("Ymd")."/".$this->sourceFile;
		$first = true;
		$handle = fopen($source, 'r');
		$lines = 0;
		while (!feof($handle)) {
			$lines++;
			$line = "";
			#$line = stream_get_line($handle, 1000000, "\n"); 
			$line = fgets($handle);
			if ($first) {
				$first = false;#elso sor csak title
			} else {
				if ($line != false) {
					if ($this->actualNewLine == 5000) {
						$this->actualNewLine = 0;
						DB::reconnect();
					}
					$this->setByLine($line);
					$this->actualNewLine = $this->actualNewLine + 1;
				}
			}
			
			#unset($line);
			if ($this->actualNewLine == 1) {
				file_put_contents('actline.txt', $lines);
			}
			/*$memoryUsed = memory_get_usage();
			$memoryUsed = (double)$memoryUsed/1024;
			$memoryUsed = (double)$memoryUsed/1024;
			$s = 'memoriused.txt';
			file_put_contents($s,$memoryUsed."MB");*/
			
		}
		$this->setDeleted();
	}
        
        public function newApiGetDeletedCount() {
            $deleteUrl = "http://www.youporn.com/api/webmasters/deleted_videos/";
            $jsonDatas = file_get_contents($deleteUrl);
            $datas = json_decode($jsonDatas);
            return $datas->count;
        }
        
        public function newApiGetRemoveBaseIdsToPage($page) {
            if (is_null($page)) {
            $page = 1;
        }

        $deleteUrl = "http://www.youporn.com/api/webmasters/deleted_videos/?page=3".$page;
            $jsonDatas = file_get_contents($deleteUrl);
            $datas = json_decode($jsonDatas);
            $ret = array();
            foreach($datas->videos as $video) {
                $ret[] = $video->video->video_id;
            }
            return $ret;
        }
        
        public function newApiRemoveVideos() {
            $count = $this->newApiGetDeletedCount();
            $maxPage = ceil($count/30);
            for ($i = $maxPage;$i>0;$i--) {
                $baseIds = $this->newApiGetRemoveBaseIdsToPage($i);
                foreach($baseIds as $baseId) {
                    $video = Video::where('base_video_id', '=', $baseId)->where('partner_id', '=', 3)->get();
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
            return 10198;
        }
        
        public function newApiGetImportDatasToPage($page = 1) {
            $url = "http://www.youporn.com/api/webmasters/search/?thumbsize=big&page=".$page;
            $jsonDatas = file_get_contents($url);
            $datas = json_decode($jsonDatas);
            return $datas->video;
        }
        
        public function newApiFullImport() {
            $limit = 29;
            $maxPage = $this->newApiGetImportCount();
            $j = 0;
            for($i = $maxPage;$i > 0;$i--) {
                $videoDatas = $this->newApiGetImportDatasToPage($i);
                $j++;
                if($j == 100) {
                    echo $i."<br/>";
                    $j = 0;
                }
                foreach($videoDatas as $rawVideo) {
                    $tmpQuery = Video::where('partner_id', '=', 3);
                    $tmpQuery->where('video_id', '=', $rawVideo->video_id);
                    $tmpVideo = $tmpQuery->count();
                    if (is_null($tmpVideo) || $tmpVideo < 1 || $tmpVideo === false) {
                        #ekkor kell import
                        $this->newApiImportVideo($rawVideo);
                    }
                }
            }
        }
}