<?php
namespace yii2lab\lang\helpers;

use Yii;
use yii\helpers\FileHelper;
use woop\foundation\helpers\Helper;

class LangHelper {
	
	public static function module($name, $message, $params = [], $language = null)
	{
		$nameArr = explode('/', $name);
		$moduleName = $nameArr[0];
		$fileName = $nameArr[1] ?: 'main';
		if($moduleName == 'this' || empty($moduleName)) {
			$moduleName = Yii::$app->controller->module->id;
		}
		$pathName = 'modules/'.$moduleName;
		if(empty(Yii::$app->i18n->translations[$pathName.'/*'])) {
			self::registerModule($moduleName);
		}
		return Yii::t($pathName.'/'.$fileName, $message, $params, $language);
	}
	
	private static function getLangFileNames($dir)
	{
		$dir = Yii::getAlias($dir);
		/*if(!is_dir($dir)) {
			return [];
		}*/
		$options['only'][] = '*.php';
		$fileList = FileHelper::findFiles($dir, $options);
		$fileList = array_map(function($file) {
			return pathinfo($file, PATHINFO_FILENAME);
		}, $fileList);
		return $fileList;
	}
	
	private static function genFileMap($moduleName, $dir)
	{
		$categoryList = self::getLangFileNames($dir);
		if(empty($categoryList)) {
			return [];
		}
		foreach($categoryList as $category) {
			$map['modules/'.$moduleName.'/'.$category] = $category.'.php';
		}
		return $map;
	}
	
	private static function registerModule($moduleName)
	{
		$moduleClass = self::getModuleClass($moduleName);
		$langDir = self::getModuleLangDir($moduleClass);
		$dir = Yii::getAlias('@' . $langDir);
		if (is_dir($dir)) {
			Yii::$app->i18n->translations['modules/'.$moduleName.'/*'] = [
				'class'		  => 'yii\i18n\PhpMessageSource',
				'sourceLanguage' => 'xx-XX',
				'basePath'	   => $dir,
				'fileMap'		=> self::genFileMap($moduleName, $dir),
			];
		}
	}
	
	private static function getModuleLangDir($moduleClass)
	{
		if (property_exists($moduleClass, 'langDir') && !empty($moduleClass::$langDir)) {
			$langDir = $moduleClass::$langDir;
		} else {
			$moduleClassFile = str_replace('\\', '/', $moduleClass);
			$moduleDir = pathinfo($moduleClassFile, PATHINFO_DIRNAME);
			$langDir = $moduleDir . '/messages';
		}
		return $langDir;
	}
	
	private static function getModuleClass($moduleName)
	{
		$moduleConfig = config('modules.' . $moduleName);
		if (is_array($moduleConfig)) {
			$moduleClass = $moduleConfig['class'];
		} else {
			$moduleClass = $moduleConfig;
		}
		return $moduleClass;
	}
	
}
