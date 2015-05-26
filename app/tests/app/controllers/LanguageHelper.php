<?php

class LanguageHelper extends BaseController {

	protected function getValidNewUrl($oldLanguage, $newLanguage, $oldUrl) {
		if (!Language::isValidLanguage($newLanguage)) $newLanguage = 'en';
		if ($newLanguage == $oldLanguage) return $oldUrl;
		$pos = strpos($oldUrl, $oldLanguage);
		if($pos !== false) return substr_replace($oldUrl, $newLanguage, $pos, 2);
		return $oldUrl;
	}

	public function changeLanguage() {
		$oldLanguage = Input::get('oldlanguage');
		$newLanguage = Input::get('newlanguage');
		$oldUrl = Input::get('oldurl');
		$newUrl = $this->getValidNewUrl($oldLanguage, $newLanguage, $oldUrl);
		return Redirect::to($newUrl);
	}
}