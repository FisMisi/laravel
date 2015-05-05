<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class StaticContent extends Eloquent implements RemindableInterface {

	use RemindableTrait;

	protected $table = 'static_contents';
	
	protected $primaryKey = 'static_content_id';
	
	#frontend helper (admin is hasznalja, cache eseten majd lehet kette kell szedni)
	public static function getLangStaticContent($title, $lang) {
		return self::where('active', 1)->where('class', 'like', $title)->where('language', 'like', $lang)->first();
	}
	
	#csak admin oldalon hasznalhato!
	public static function getFreeLangToSc() {
		$baseScs = self::where('active', 1)->where('language', 'like', 'en')->get();
		$langs = Language::getLangShortList(true);
		$ret = "var usable__new_class_sc = ['en'];var usable_long__new_class_sc = ['English'];";
		$classes = "var sc__classes = ['new_class_sc'";
		foreach($baseScs as $baseSc) {
			$idLangs = $langs;
			$idLongs = array();
			$subScs = self::where('class', 'like', $baseSc->class)->get();
			foreach($subScs as $subSc) {
				unset($idLangs[$subSc->language]);
			}
			foreach($idLangs as $idLang) {
				$idLongs[] = Language::getLongToShort($idLang);
			}
			if (count($idLangs) != 0) {
				$ret.= "var usable__".$baseSc->class." = ['".implode("','",$idLangs)."'];";
				$ret.= "var usable_long__".$baseSc->class." = ['".implode("','",$idLongs)."'];";
				$classes.= ",'".$baseSc->class."'";
			}
			
		}
		return $ret.$classes."];";
	}
	
	public static function getNotFullLangClass($needNew = false) {
		$langsRaw = Language::getLangShortList();
		$langNum = count($langsRaw);
		$scs = self::whereIn('language', $langsRaw)->get();
		#var_dump($scs);
		$classes = array();
		foreach($scs as $sc) {
			if (!isset($classes[$sc->class])) $classes[$sc->class] = 1;
			else $classes[$sc->class]++;
		}
		$ret = $needNew ? array('new_class_sc' => 'New SC') : array();
		foreach ($classes as $key => $value) {
			if ($value < $langNum) $ret[$key] = $key; 
		}
		return $ret;
	}
	
	public static function getClasses() {
		$scs = self::where('active', 1)->get();
		$ret = array();
		foreach($scs as $sc) {
			$ret[$sc->class] = $sc->class;
		}
		return $ret;
	}
	
	public static function getDatasToAll($lang = null, $active = 2, $limit = 20, $page = 1) {
		
		if ($page > 0) {
			$page--;
		}
	
		$active = is_null($active) ? 2 : $active;
		if ($active == 1 || $active == 0) {
			if ($lang != null) {
				$query = self::where('active', $active)->where('language', 'like', $lang)->take($limit);
				if ($page > 0) {
					$query->skip($page*$limit);
				}
				$all = $query->get();
				$count = self::where('active', $active)->where('language', 'like', $lang)->count();
			} else {
				$query = self::where('active', $active)->take($limit);
				if ($page > 0) {
					$query->skip($page*$limit);
				}
				$all = $query->get();
				$count = self::where('active', $active)->count();
			}
			
		} else {
			if ($lang != null) {
				$query = self::Where('language', 'like', $lang)->take($limit);
				if ($page > 0) {
					$query->skip($page*$limit);
				}
				$all = $query->get();
				$count = self::Where('language', 'like', $lang)->count();
			} else {
				$query = self::take($limit);
				if ($page > 0) {
					$query->skip($page*$limit);
				}
				$all = $query->get();
				$count = self::count();
			}
		}
		
		$ret = array();
		$i= 0;
		foreach($all as $sc) {
			$ret[$i]['active'] = $sc->active;
			$ret[$i]['id'] = $sc->static_content_id;
			$ret[$i]['title'] = $sc->title;
			$ret[$i]['class'] = $sc->class;
			$ret[$i]['lang'] = Language::getLongToShort($sc->language);
			if ($sc->parent_id == NULL) {
				$ret[$i]['parent'] = "No Parent";
			} else {
				$parent = self::where('active', 1)->where('static_content_id', $sc->parent_id)->first();
				if (!$parent->title) {
					$ret[$i]['parent'] = "No Active Parent!";
				} else {
					$ret[$i]['parent'] = $parent->title."(".$parent->language.")";
				}
			}
			$i++;
		}
		$return = array();
		$return['scs'] = $ret;
		$return['count'] = $count;
		return $return;
	}
}