<?php

namespace yii2module\lang\domain\helpers;

use Yii;
use yii2lab\helpers\DomainHelper;
use yii2lab\helpers\ModuleHelper;
use yii2lab\helpers\yii\FileHelper;
use yii2module\lang\domain\enums\LanguageEnum;

class LangHelper {
	
	const PREFIX_MODULE = 'module:';
	const PREFIX_DOMAIN = 'domain:';
	const MESSAGES_DIR = 'messages';
	const ALL = '*';
	
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
	
	public static function register($category) {
		$data = self::parseCategory($category);
		if(empty($data['bundle'])) {
			return $category;
		}
		LangHelper::registerBundle($data['bundle']);
		$category = LangHelper::getId($data['bundle'], $data['category']);
		return $category;
	}
	
	public static function addTranslation($id, $basePath, $fileMap = null) {
		$config = self::generateConfig($basePath, $fileMap);
		Yii::$app->i18n->translations[$id] = $config;
	}
	
	private static function generateConfig($basePath, $fileMap) {
		$config = [
			'class' => 'yii2module\lang\domain\i18n\PhpMessageSource',
			'sourceLanguage' => LanguageEnum::SOURCE,
			'basePath' => $basePath,
		];
		if(!empty($fileMap)) {
			$config['fileMap'] = $fileMap;
		}
		if(is_object(Yii::$app)) {
			$translationEventHandler = Yii::$app->lang->language->translationEventHandler;
			if($translationEventHandler) {
				$config['on missingTranslation'] = $translationEventHandler;
			}
		}
		return $config;
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
		$category = self::ALL;
		$id = LangHelper::getId($bundleName, $category);
		if(isset(Yii::$app->i18n->translations[$id])) {
			return $id;
		}
		$bundleNameOnly = self::extractCleanBundleName($bundleName);
		$basePath = DomainHelper::messagesAlias($bundleNameOnly);
		if(empty($basePath)) {
			$basePath = ModuleHelper::messagesAlias($bundleNameOnly);
		}
		if(!empty($basePath)) {
			self::addToI18n($basePath, $bundleName, $category);
		}
		return $id;
	}
	
	private static function extractCleanBundleName($bundleName) {
		$bundleNameArr = explode(':', $bundleName);
		return count($bundleNameArr) == 1 ? $bundleNameArr[0] : $bundleNameArr[1];
	}
	
	private static function addToI18n($basePath, $bundleName, $category) {
		$dir = FileHelper::getAlias($basePath);
		if(is_dir($dir)) {
			$id = self::getId($bundleName, $category);
			$fileMap = self::genFileMap($bundleName, $dir);
			self::addTranslation($id, $basePath, $fileMap);
		}
	}
	
	private static function findFiles($dir) {
		$dir = Yii::getAlias($dir);
		$messageDir = $dir . DS . Yii::$app->language;
		if(!is_dir($messageDir)) {
			return [];
		}
		$options['only'][] = '*.php';
		$fileList = FileHelper::scanDir($messageDir);
		$fileList = array_map(function ($file) {
			return pathinfo($file, PATHINFO_FILENAME);
		}, $fileList);
		return $fileList;
	}
	
	private static function genFileMap($bundleName, $dir) {
		$categoryList = self::findFiles($dir);
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
		if(DomainHelper::has($bundleName)) {
			return self::PREFIX_DOMAIN;
		}
		if(ModuleHelper::has($bundleName)) {
			return self::PREFIX_MODULE;
		}
		return null;
	}
	
}
