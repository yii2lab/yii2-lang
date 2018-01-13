<?php

namespace yii2module\lang\domain\helpers;

use yii2lab\domain\Domain;

class DomainConfigHelper {
	
	public static function addTranslations($config) {
		$config = self::addTranslationsOfType($config);
		return $config;
	}
	
	private static function addTranslationsOfType($config) {
		foreach($config as $name => &$data) {
			if(self::isDomain($data) && !empty($data['translations'])) {
				$config = DomainConfigHelper::addTranslation($config, $data['translations']);
				unset($data['translations']);
			}
		}
		return $config;
	}
	
	private static function addTranslation($config, $translations) {
		if(empty($translations)) {
			return $config;
		}
		foreach($translations as $id => $translationConfig) {
			$translationConfig = self::normalizeTranslations($translationConfig);
			$config['components']['i18n']['translations'][$id] = $translationConfig;
			$config['components']['i18n']['translations']['domain:' . $id] = self::addPrefix($translationConfig);
		}
		return $config;
	}
	
	private static function normalizeTranslations($config) {
		$config['class'] = 'yii2module\lang\domain\i18n\PhpMessageSource';
		$config['on missingTranslation'] = ['yii2module\lang\domain\handlers\TranslationEventHandler', 'handleMissingTranslation'];
		return $config;
	}
	
	private static function addPrefix($translationConfig) {
		if(empty($translationConfig['fileMap'])) {
			return $translationConfig;
		}
		foreach($translationConfig['fileMap'] as $alias => $file) {
			$translationConfig['fileMap']['domain:' . $alias] = $translationConfig['fileMap'][$alias];
			unset($translationConfig['fileMap'][$alias]);
		}
		return $translationConfig;
	}
	
	private static function isDomain($config) {
		if(empty($config['class'])) {
			return false;
		}
		if($config['class'] == Domain::class || is_subclass_of($config['class'], Domain::class)) {
			return true;
		}
		return false;
	}
}
