<?php

namespace yii2module\lang\domain\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\helpers\ModuleHelper;
use yii2lab\helpers\yii\FileHelper;

class LangHelper {
	
	const PREFIX_MODULE = 'module:';
	const PREFIX_DOMAIN = 'domain:';
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
	
	public static function register($category) {
		$data = self::parseCategory($category);
		if(empty($data['bundle'])) {
			return $category;
		}
		$id = LangHelper::getId($data['bundle'], '*');
		if(empty(Yii::$app->i18n->translations[$id])) {
			LangHelper::registerBundle($data['bundle']);
		}
		$category = LangHelper::getId($data['bundle'], $data['category']);
		return $category;
	}
	
	private static function parseCategory($category) {
		$items = explode('/', $category);
		if(count($items) > 1) {
			$bundleName = $items[0];
			// todo: заменить this на реальные имена
			if($bundleName == 'this' || empty($bundleName)) {
				$bundleName = Yii::$app->controller->module->id;
			}
			// todo: костыль
			$categoryName = isset($items[1]) ? $items[1] : 'main';
			return [
				'bundle' => $bundleName,
				'category' => $categoryName,
			];
		}
		return [
			'bundle' => null,
			'category' => $category,
		];
	}
	
	private static function getId($bundle, $category = null) {
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
	
	private static function registerBundle($bundleName) {
		$langDirAlias = self::getDomainLangDir($bundleName);
		if(empty($langDirAlias)) {
			$langDirAlias = self::getModuleLangDir($bundleName);
		}
		if(!empty($langDirAlias)) {
			self::addToI18n($langDirAlias, $bundleName);
		}
	}
	
	private static function addToI18n($langDirAlias, $bundleName) {
		$dir = FileHelper::getAlias($langDirAlias);
		if(is_dir($dir)) {
			$id = self::getId($bundleName, '*');
			$config = [
				'class' => 'yii\i18n\PhpMessageSource',
				'sourceLanguage' => 'xx-XX',
				'basePath' => $langDirAlias,
				'fileMap' => self::genFileMap($bundleName, $dir),
			];
			$translationEventHandler = Yii::$app->lang->language->translationEventHandler;
			if($translationEventHandler) {
				$config['on missingTranslation'] = $translationEventHandler;
			}
			Yii::$app->i18n->translations[$id] = $config;
		}
	}
	
	private static function getLangFileNames($dir) {
		$dir = Yii::getAlias($dir);
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
	
	private static function getBundleTypePrefix($bundleName) {
		if(Yii::$app->has($bundleName)) {
			return self::PREFIX_DOMAIN;
		}
		if(config('modules.' . $bundleName)) {
			return self::PREFIX_MODULE;
		}
		return null;
	}
	
	private static function getDomainLangDir($bundleName) {
		if(!Yii::$app->has($bundleName)) {
			return false;
		}
		$domain = ArrayHelper::getValue(Yii::$app, $bundleName);
		return self::getBundlePath($domain->path);
	}
	
	private static function getModuleLangDir($bundleName) {
		$moduleClass = ModuleHelper::getClass($bundleName);
		if(!class_exists($moduleClass)) {
			return null;
		}
		if(property_exists($moduleClass, 'langDir') && !empty($moduleClass::$langDir)) {
			return $moduleClass::$langDir;
		}
		$path = FileHelper::up($moduleClass,1);
		return self::getBundlePath($path);
	}
	
	private static function getBundlePath($path) {
		if(empty($path)) {
			return false;
		}
		$alias = FileHelper::normalizeAlias($path . SL . self::MESSAGES_DIR);
		$dir = Yii::getAlias($alias);
		if(!is_dir($dir)) {
			return false;
		}
		return $alias;
	}
	
}
