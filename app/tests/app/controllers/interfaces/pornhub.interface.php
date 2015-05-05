<?php

@require_once("ruby.interface.php");
/*@require_once("../../models/ExternalCategory.php");
@require_once("../../models/Tag.php");
@require_once("../../models/Video.php");
@require_once("../../models/Star.php")
@require_once("../../models/VideosStars.php");
*/
class PornhubInterface extends RubyInterface {
	
	protected $partnerId;
	protected $sourceBase = "/../../../interfacedump/";
	protected $sourceFile = "pornhub.com-db.csv";
	protected $sourceDeleteFile = "deleted.csv";
	protected $iframeBase = "http://www.pornhub.com/embed/";
	
	public $linesHelper;
	
	protected function getVideoBaseIdFromIframe($iframe) {
		$posStart = strpos($iframe, '"');
		$ret = substr($iframe, $posStart+1);
		$posEnd = strpos($ret, '"');
		$ret = substr($ret, 0, $posEnd);
		return str_replace($this->iframeBase, "", $ret);
	}
	
	protected function setByLine($line) {
		list($iframe, $thumb, $thumbs, $title, $tags, $categories, $stars, $time) = explode("|", $line);
		$videoBaseId = $this->getVideoBaseIdFromIframe($iframe);
		$hasVideo = Video::hasBaseVideoId($videoBaseId, $this->partnerId);
		if ($hasVideo) return false;#ekkor nem kell feldolgozni
		
		$this->linesHelper = $this->linesHelper + 9;
		$tagArray = explode(";", $categories);
		if (!is_array($tagArray) || count($tagArray) == 0) {
			$tagArray[0] = 'video';
		}
		$thumbsArray = explode(";", $thumbs);
		
		
		$video = new Video();
		$video->base_video_id = $videoBaseId;
		$video->video_name = $title;
		$seoRet = Video::getSeoNameToTitle($title, $tagArray[0], $this->partnerId, $video->base_video_id);
		$video->video_seo_name = $seoRet['seoName'];
		$video->valid_seo = $seoRet['validSeo'];#DONE
		
		$video->default_thumb = $thumbsArray[0];
		$h = (integer)($time/3600);
		$m = (integer)(($time-3600*$h)/60);
		$s = ($time-3600*$h-60*$m);
		if ($h > 23) {
			$h = 23;
		}
		$video->length = str_pad($h,2, '0', STR_PAD_LEFT).":".str_pad($m,2, '0', STR_PAD_LEFT).":".str_pad($s,2, '0', STR_PAD_LEFT);#Done
		$video->video_flash_link = $iframe;
		$video->partner_id = $this->partnerId;
		$video->active = 0;
		$video->save();
		
		$ie = new ImportError();
		$ie->video_id = $video->video_id;
		$ie->video_base_id = $video->base_video_id;
		$ie->partner_id = $video->partner_id;
		$ie->type = "video save";
		$ie->save();
		
		$sv = new VideoSee();
		$sv->video_id = $video->video_id;
		$sv->see_count = 0;
		$sv->see_count_l = 0;
		$sv->timestamps = false;
		$sv->save();
		
		$ie = new ImportError();
		$ie->video_id = $video->video_id;
		$ie->video_base_id = $video->base_video_id;
		$ie->partner_id = $video->partner_id;
		$ie->type = "see video save";
		$ie->save();
		
		$video->setTagsFromArray($tagArray, $this->partnerId);#DONE
		
		$ie = new ImportError();
		$ie->video_id = $video->video_id;
		$ie->video_base_id = $video->base_video_id;
		$ie->partner_id = $video->partner_id;
		$ie->type = "set tag done";
		$ie->save();
		
		
		$video->generateThumbsDirectory($thumbsArray);#Done
		
		$ie = new ImportError();
		$ie->video_id = $video->video_id;
		$ie->video_base_id = $video->base_video_id;
		$ie->partner_id = $video->partner_id;
		$ie->type = "thumbs generated";
		$ie->save();
		
		
		$starArray = explode(";", $stars);
		$iteratorLines = 0;
		$iteratorLines2 = 0;
		foreach($starArray as $starName) {
			$iteratorLines++;
			$iteratorLines2++;
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
			}
			if ($iteratorLines > 999) {
				$iteratorLines = 0;
				$ph = 'phub.txt';
				file_put_contents($ph,$iteratorLines2."L");
			}
			
		}
	}
	
	protected function setDeletedByLine($line) {
		$baseId = substr($line, 46);
		Video::setInactiveVideoByBaseVideoId($baseId, 0, "removedVideo", $this->partnerId);
	}
	
	protected function setDeleted() {
		$source = __DIR__.$this->sourceBase.date("Ymd")."/".$this->sourceDeleteFile;
		$handle = fopen($source, 'r');
		while(!feof($handle)) {
			$line = fgets($handle); 
			if ($line != false) $this->setDeletedByLine($line);
		}
	}
	
	public function fullRun() {
		#asdasdasd
		DB::disableQueryLog();
		$this->partnerId = Partner::getIdToName('Pornhub');
		$source = __DIR__.$this->sourceBase.date("Ymd")."/".$this->sourceFile;
		$handle = fopen($source, 'r');
		if($handle !== false) {
			$fullLines = 0;
			$this->linesHelper = 0;
			while (!feof($handle)) {
				$this->linesHelper = $this->linesHelper + 1;
				$fullLines = $fullLines + 1;
				if ($this->linesHelper > 50000) {
					$this->linesHelper = 0;
					DB::reconnect();
					#file_put_contents('actline.txt', $fullLines."Lines");
				}
				$line = fgets($handle); 
				if ($line != false) $this->setByLine($line);
			}
			$this->setDeleted();	
		}
	}


        public function newApiGetDeletedCount() {
            return 16182;
        }

        public function newApiGetRemoveBaseIdsToPage($page) {
            if (is_null($page)) {
                $page = 1;
            }

            $deleteUrl = "http://www.pornhub.com/webmasters/deleted_videos?page=".$page;
            $jsonDatas = file_get_contents($deleteUrl);
            $datas = json_decode($jsonDatas);
            $ret = array();
            foreach($datas->videos as $video) {
                $ret[] = $video->vkey;
            }
            return $ret;
        }
        
         public function newApiRemoveVideos() {
            $count = $this->newApiGetDeletedCount();
            $maxPage = ceil($count/30);
            $maxPage = $this->newApiGetDeletedCount();
            for ($i = $maxPage;$i>0;$i--) {
                $baseIds = $this->newApiGetRemoveBaseIdsToPage($i);
                foreach($baseIds as $baseId) {
                    $video = Video::where('base_video_id', '=', $baseId)->where('partner_id', '=', 2)->get();
                    if(count($video) == 1) {
                        #echo $video[0]->base_video_id."<br/>";
                        $video[0]->active2 = 0;
                        $video[0]->inactivation_reason = "remove by New Api";
                        $video[0]->inactivation_time = date('Y-m-d H:i:s');
                        $video[0]->inactivation_user_id = 0;
                        $video[0]->save();
                    } else {
                        #echo "ERROR BASE_VIDEO_IS:".$baseId."<br/>";
                    }
                }
                echo "page:".$i."<br/>";
            }   
        }
        
        public function newApiGetImportCount() {
            return 4504;
        }
        
        public function newApiGetImportDatasToPage($page = 1) {
            $url = "http://www.pornhub.com/webmasters/search?id=44bc40f3bc04f65b7a35&thumbsize=large&page=".$page;
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
        
        public function newApiSetStarsToVideo($videoId, $stars) {
            foreach ($stars as $star) {
                $starName = ucwords(strtolower($star->pornstar_name));
                $starObj = Star::where("star_name", "=", $starName)->first();
                if (is_null($starObj)) {#ha nincs meg az eloadomuvesz a db-ben
                    $starObj = new Star();
                    $starObj->star_name = $starName;
                    $starObj->save();
                }
                $id = VideosStars::where('video_id', $videoId)->where("star_id", $starObj->star_id)->first();
                if (is_null($id)) {
                        $new = new VideosStars();
                        $new->video_id = $videoId;
                        $new->star_id = $starObj->star_id;
                        $new->save();
                }
            }
        }
        
        public function newApiImportVideo() {
            $video = new Video();
            $video->length = self::properLengthFormat($rawVideo->duration);
            $video->base_video_id = $rawVideo->video_id;
            $video->partner_id = 2;
            $video->video_name = $rawVideo->title;
            $seoRet = Video::getSeoNameToTitle($rawVideo->title, $rawVideo->tags[0]->tag_name, 2, $video->base_video_id);
            $video->video_seo_name = $seoRet['seoName'];
            $video->valid_seo = $seoRet['validSeo'];
            $video->default_thumb = $rawVideo->thumbs[0]->src;
            $video->video_flash_link = Video::getFlashLinkToToBaseVideoIdRt($video->base_video_id);
            $video->rating = $rawVideo->rating;#@todo refact to 1-5 from 0-100
            $video->rating_number = $rawVideo->ratings;
            $video->sum_rating = round($video->rating*$video->rating_number);
            $video->save();
            
            $vs = new VideoSee();
            $vs->video_id = $video->video_id;
            $vs->see_count = $rawVideo->views;
            $vs->timestamps = false;
            $vs->save();
            
            $video->setTags($rawVideo->tags);
            $video->generateThumbsDirectoryRT($rawVideo->thumbs);
            
            #@todo ide gyünnek előadónénik!
            #$rawVideo->pornstars[0]->pornstar_name
            $this->newApiSetStarsToVideo($video->video_id, $rawVideo->pornstars);
        }
        
        public function newApiFullImport() {
            $limit = 30;
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
                    $tmpQuery = Video::where('partner_id', '=', 2);
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