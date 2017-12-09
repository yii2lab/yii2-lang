<?php

namespace yii2module\lang\domain;

use yii2lab\domain\enums\Driver;

class Domain extends \yii2lab\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
				'language' => Driver::DISC,
				'store' => APP == API ? Driver::HEADER : Driver::COOKIE,
			],
			'services' => [
				'language',
			],
		];
	}
	
}