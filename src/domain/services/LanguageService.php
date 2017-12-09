<?php

namespace yii2module\lang\domain\services;

use yii2lab\domain\services\ActiveBaseService;

class LanguageService extends ActiveBaseService {
	
	public function oneCurrent() {
		return $this->repository->oneCurrent();
	}
	
}
