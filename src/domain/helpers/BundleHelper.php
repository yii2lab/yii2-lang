<?php

namespace yii2module\lang\domain\helpers;

use Yii;
use yii2lab\domain\helpers\DomainHelper;
use yii2lab\extension\common\helpers\ModuleHelper;
use yii2lab\extension\yii\helpers\FileHelper;
use yii2module\lang\domain\enums\LanguageEnum;

class BundleHelper {
	
	const ALL = '*';
	
	public static function register($category) {
		$data = self::parseCategory($category);
		if(empty($data['bundle'])) {
			return $category;
		}
		BundleHelper::registerBundle($data['bundle']);
		$category = LangHelper::getId($data['bundle'], $data['category']);
		return $category;
	}
	
	private static function parseCategory($category) {
		$items = explode('/', $category);
		if(count($items) > 1) {
			$bundleName = $items[0];
			// todo: @deprecated заменить this на реальные имена
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
			$id = LangHelper::getId($bundleName, $category);
			$fileMap = MapHelper::genFileMap($bundleName, $dir);
			self::addTranslation($id, $basePath, $fileMap);
		}
	}
	
	private static function addTranslation($id, $basePath, $fileMap = null) {
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
		/*if(is_object(Yii::$app)) {
			$translationEventHandler = \App::$domain->lang->language->translationEventHandler;
			if($translationEventHandler) {
				$config['on missingTranslation'] = $translationEventHandler;
			}
		}*/
		return $config;
	}
	
}
