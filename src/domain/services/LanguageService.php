<?php

namespace yii2module\lang\domain\services;

use yii2lab\domain\services\ActiveBaseService;

class LanguageService extends ActiveBaseService {
	
	private $isInited = false;
	
	public function init() {
		if($this->isInited) {
			return;
		}
		$this->isInited = true;
		if(APP != CONSOLE) {
			$this->repository->initLanguage();
		}
	}
	
	/*public function oneMain() {
		return $this->repository->oneMain();
	}
	
	public function isExistsByCode($code) {
		return $this->repository->isExistsByCode($code);
	}
	
	public function oneByCode($code) {
		return $this->repository->oneByCode($code);
	}*/
	
	public function oneCurrent() {
		return $this->repository->oneCurrent();
	}
	
	public function saveCurrent($language) {
		return $this->repository->saveCurrent($language);
	}
	
}
