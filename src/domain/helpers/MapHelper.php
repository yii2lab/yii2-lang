<?php

namespace yii2module\lang\domain\helpers;

use Yii;
use yii2lab\helpers\yii\FileHelper;

class MapHelper {
	
	public static function genFileMap($bundleName, $dir) {
		$categoryList = self::findFiles($dir);
		if(empty($categoryList)) {
			return [];
		}
		foreach($categoryList as $category) {
			$id = LangHelper::getId($bundleName, $category);
			$map[$id] = $category . '.php';
		}
		return $map;
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
	
}
