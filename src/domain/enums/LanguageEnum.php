<?php

namespace yii2module\lang\domain\enums;

use yii2lab\extension\enum\base\BaseEnum;

class LanguageEnum extends BaseEnum {
	
	const RU = 'ru';
	const EN = 'en';
	const SOURCE = 'xx';
	
	public static function code($locale) {
		$localeArr = explode('-', $locale);
		if(count($localeArr) > 0) {
			return strtolower($localeArr[0]);
		} else {
			return null;
		}
	}
	
}
