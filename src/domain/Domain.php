<?php

namespace yii2module\lang\domain;

use yii2lab\domain\enums\Driver;

/**
 * Class Domain
 * 
 * @package yii2module\lang\domain
 * @property-read \yii2module\lang\domain\interfaces\services\LanguageInterface $language
 * @property-read \yii2module\lang\domain\interfaces\repositories\RepositoriesInterface $repositories
 */
class Domain extends \yii2lab\domain\Domain {
	
	public function config() {
		if(APP == API) {
			$storeDriver = Driver::HEADER;
		} elseif(APP == CONSOLE) {
			$storeDriver = Driver::MOCK;
		} else {
			$storeDriver = Driver::COOKIE;
		}
		return [
			'repositories' => [
				'language' => Driver::FILEDB,
				'store' => $storeDriver,
			],
			'services' => [
				'language' => [],
			],
		];
	}
	
}
