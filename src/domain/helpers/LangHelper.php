<?php

namespace yii2module\lang\domain\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii2lab\helpers\MenuHelper;

class LangHelper {
	
	public static function extract($message) {
		if(empty($message)) {
			return '';
		}
		if(is_array($message)) {
			$message = t($message[0], $message[1]);
		}
		return $message;
	}
	
	public static function current() {
		$all = ArrayHelper::map(Yii::$app->lng->getAllLanguages(), 'code', 'title');
		$lang = self::locale2lang(Yii::$app->language);
		return $all[ $lang ];
	}
	
	public static function locale2lang($lang) {
		$langArr = explode('-', $lang);
		return $langArr[0];
	}
	
	public static function allForMenu() {
		$all = ArrayHelper::map(Yii::$app->lng->getAllLanguages(), 'code', 'title');
		$items = [];
		foreach($all as $name => $title) {
			$items[] = [
				'label' => $title,
				'url' => 'lang/default/change?language=' . $name,
				'linkOptions' => ['data-method' => 'post'],
			];
		}
		return MenuHelper::gen($items);
	}
	
	public static function module($name, $message, $params = [], $language = null) {
		$nameArr = explode('/', $name);
		$moduleName = $nameArr[0];
		$fileName = isset($nameArr[1]) ? $nameArr[1] : 'main';
		if($moduleName == 'this' || empty($moduleName)) {
			$moduleName = Yii::$app->controller->module->id;
		}
		$pathName = 'modules/' . $moduleName;
		if(empty(Yii::$app->i18n->translations[ $pathName . '/*' ])) {
			self::registerModule($moduleName);
		}
		return Yii::t($pathName . '/' . $fileName, $message, $params, $language);
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
	
	private static function genFileMap($moduleName, $dir) {
		$categoryList = self::getLangFileNames($dir);
		if(empty($categoryList)) {
			return [];
		}
		foreach($categoryList as $category) {
			$map[ 'modules/' . $moduleName . '/' . $category ] = $category . '.php';
		}
		return $map;
	}
	
	public static function registerModule($moduleName) {
		$moduleClass = self::getModuleClass($moduleName);
		$langDir = self::getModuleLangDir($moduleClass);
		
		if(empty($langDir)) {
			if(!Yii::$app->has($moduleName)) {
				return false;
			}
			$domain = ArrayHelper::getValue(Yii::$app, $moduleName);
			$langDir = $domain->path;
			$langDir = str_replace('\\', '/', $langDir);
			$langDir = $langDir . '/messages';
			$langDir = '@' . $langDir;
		}
		
		if($langDir[0] == '@') {
			$dir = Yii::getAlias($langDir);
		} else {
			$dir = ROOT_DIR . DS . $langDir;
		}
		if(is_dir($dir)) {
			Yii::$app->i18n->translations[ 'modules/' . $moduleName . '/*' ] = [
				'class' => 'yii\i18n\PhpMessageSource',
				'sourceLanguage' => 'xx-XX',
				'basePath' => $dir,
				'fileMap' => self::genFileMap($moduleName, $dir),
			];
		}
	}
	
	private static function getModuleLangDir($moduleClass) {
		if(!class_exists($moduleClass)) {
			//Throw new ServerErrorHttpException('module_not_found');
			return null;
		}
		if(property_exists($moduleClass, 'langDir') && !empty($moduleClass::$langDir)) {
			$langDir = $moduleClass::$langDir;
		} else {
			$langDir = null;
			/* $moduleClassFile = str_replace('\\', '/', $moduleClass);
			$moduleDir = pathinfo($moduleClassFile, PATHINFO_DIRNAME);
			$langDir = '@' . $moduleDir . '/messages'; */
		}
		return $langDir;
		
		
	}
	
	private static function getModuleClass($moduleName) {
		$moduleConfig = config('modules.' . $moduleName);
		if(is_array($moduleConfig)) {
			$moduleClass = $moduleConfig['class'];
		} else {
			$moduleClass = $moduleConfig;
		}
		return $moduleClass;
	}
}
