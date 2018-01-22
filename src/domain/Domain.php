<?php

namespace yii2module\lang\domain;

use yii2lab\domain\enums\Driver;

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
				'language' => Driver::DISC,
				'store' => $storeDriver,
			],
			'services' => [
				'language' => [
					'translationEventHandler' => ['yii2module\lang\domain\handlers\TranslationEventHandler', 'handleMissingTranslation'],
				],
			],
		];
	}
	
}
