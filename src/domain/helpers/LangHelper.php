<?php

namespace yii2module\lang\domain\helpers;

use yii2lab\domain\helpers\DomainHelper;
use yii2lab\extension\common\helpers\ModuleHelper;

class LangHelper {
	
	const PREFIX_MODULE = 'module:';
	const PREFIX_DOMAIN = 'domain:';
	
	public static function normalizeTranslation($config) {
		$config['class'] = 'yii2module\lang\domain\i18n\PhpMessageSource';
		return $config;
	}
	
	public static function getId($bundle, $category = null) {
		$bundleArray = explode(':', $bundle);
		$hasType = count($bundleArray) > 1;
		if(!$hasType) {
			$typePrefix = self::getBundleTypePrefix($bundleArray[0]);
			if($typePrefix) {
				$bundle = $typePrefix . $bundle;
			}
		}
		if(!empty($category)) {
			$bundle .= SL . $category;
		}
		return $bundle;
	}
	
	public static function extractList($list) {
		foreach($list as $name => $message) {
			$list[$name] = self::extract($message);
		}
		return $list;
	}
	
	public static function extract($message) {
		if(empty($message)) {
			return '';
		}
		if(is_array($message)) {
			$message = call_user_func_array('Yii::t', $message);
		}
		return $message;
	}
	
	/**
	 * @param $lang
	 *
	 * @return mixed
	 * @deprecated moved to yii2module\lang\domain\enums\LanguageEnum::code
	 */
	public static function locale2lang($lang) {
		$langArr = explode('-', $lang);
		return $langArr[0];
	}
	
	private static function getBundleTypePrefix($bundleName) {
		if(DomainHelper::has($bundleName)) {
			return self::PREFIX_DOMAIN;
		}
		if(ModuleHelper::has($bundleName)) {
			return self::PREFIX_MODULE;
		}
		return null;
	}
	
}
