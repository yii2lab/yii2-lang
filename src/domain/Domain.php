<?php

namespace yii2module\lang\domain;

use yii2lab\domain\enums\Driver;

class Domain extends \yii2lab\domain\Domain {
	
	private $isInited = false;
	
	public function init() {
		if($this->isInited) {
			return;
		}
		parent::init();
		$this->isInited = true;
		$this->language;
	}
	
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