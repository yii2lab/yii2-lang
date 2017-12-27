<?php

namespace yii2module\lang\domain\services;

use yii\web\NotFoundHttpException;
use yii2lab\domain\interfaces\services\ReadInterface;
use yii2lab\domain\services\ActiveBaseService;
use yii2module\lang\domain\entities\LanguageEntity;
use yii2module\lang\domain\interfaces\services\LanguageInterface;

class LanguageService extends ActiveBaseService implements LanguageInterface, ReadInterface {
	
	public $translationEventHandler = null;
	
	public function initCurrent() {
		if(APP != CONSOLE) {
			$this->repository->initLanguage();
		}
	}
	
	/**
	 * @return LanguageEntity
	 * @throws NotFoundHttpException
	 */
	public function oneCurrent() {
		return $this->repository->oneCurrent();
	}
	
	public function saveCurrent($language) {
		return $this->repository->saveCurrent($language);
	}
	
}
