<?php

namespace yii2module\lang\domain\enums;

use yii2lab\extension\enum\base\BaseEnum;

class LanguageEnum extends BaseEnum {
	
	const RU = 'ru-RU';
	const EN = 'en-UK';
	const SOURCE = 'xx-XX';
	
	public static function code($locale) {
		$localeArr = explode('-', $locale);
		if(count($localeArr) > 0) {
			return strtolower($localeArr[0]);
		} else {
			return null;
		}
	}
	
}
