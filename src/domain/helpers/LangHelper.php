<?php

namespace yii2module\lang\domain\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\helpers\ModuleHelper;
use yii2lab\helpers\yii\FileHelper;

class LangHelper {
	
	const PREFIX = '_bundle';
	const MESSAGES_DIR = 'messages';
	
	public static function extract($message) {
		if(empty($message)) {
			return '';
		}
		if(is_array($message)) {
			$message = call_user_func_array('Yii::t', $message);
		}
		return $message;
	}
	
	public static function locale2lang($lang) {
		$langArr = explode('-', $lang);
		return $langArr[0];
	}
	
	public static function getId($bundleName, $category = null) {
		$result = self::PREFIX . SL . $bundleName;
		if(!empty($category)) {
			$result .= SL . $category;
		}
		return $result;
	}
	
	public static function registerBundle($bundleName) {
		$langDir = self::getModuleLangDir($bundleName);
		if(empty($langDir)) {
			$langDir = self::getDomainLangDir($bundleName);
		}
		$dir = FileHelper::getAlias($langDir);
		self::addToI18n($dir, $bundleName);
	}
	
	private static function addToI18n($dir, $bundleName) {
		if(is_dir($dir)) {
			$id = self::getId($bundleName, '*');
			Yii::$app->i18n->translations[$id] = [
				'class' => 'yii\i18n\PhpMessageSource',
				'sourceLanguage' => 'xx-XX',
				'basePath' => $dir,
				'fileMap' => self::genFileMap($bundleName, $dir),
			];
		}
	}
	
	private static function getLangFileNames($dir) {
		$dir = Yii::getAlias($dir);
		/*if(!is_dir($dir)) {
			return [];
		}*/
		$options['only'][] = '*.php';
		$fileList = FileHelper::findFiles($dir, $options);
		$fileList = array_map(function ($file) {
			return pathinfo($file, PATHINFO_FILENAME);
		}, $fileList);
		return $fileList;
	}
	
	private static function genFileMap($bundleName, $dir) {
		$categoryList = self::getLangFileNames($dir);
		if(empty($categoryList)) {
			return [];
		}
		foreach($categoryList as $category) {
			$id = self::getId($bundleName, $category);
			$map[$id] = $category . '.php';
		}
		return $map;
	}
	
	private static function getDomainLangDir($bundleName) {
		if(!Yii::$app->has($bundleName)) {
			return false;
		}
		$domain = ArrayHelper::getValue(Yii::$app, $bundleName);
		$langDir = '@' . $domain->path . SL . self::MESSAGES_DIR;
		return $langDir;
	}
	
	private static function getModuleLangDir($bundleName) {
		$moduleClass = ModuleHelper::getClass($bundleName);
		if(!class_exists($moduleClass)) {
			return null;
		}
		if(property_exists($moduleClass, 'langDir') && !empty($moduleClass::$langDir)) {
			$langDir = $moduleClass::$langDir;
		} else {
			$langDir = null;
		}
		return $langDir;
	}
	
}
