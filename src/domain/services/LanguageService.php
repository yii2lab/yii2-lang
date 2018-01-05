<?php

namespace yii2module\lang\domain\services;

use Yii;
use yii\web\NotFoundHttpException;
use yii2lab\domain\interfaces\services\ReadInterface;
use yii2lab\domain\services\ActiveBaseService;
use yii2module\lang\domain\entities\LanguageEntity;
use yii2module\lang\domain\interfaces\services\LanguageInterface;

/**
 * Class LanguageService
 *
 * @package yii2module\lang\domain\services
 *
 * @property \yii2module\lang\domain\interfaces\repositories\LanguageInterface $repository
 */
class LanguageService extends ActiveBaseService implements LanguageInterface, ReadInterface {
	
	public $translationEventHandler = null;
	
	public function initCurrent() {
		if(APP == CONSOLE) {
			return;
		}
		$languageFromStore = $this->domain->repositories->store->get();
		if (!empty($languageFromStore)) {
			try {
				$entity = $this->repository->oneByLocalesOrCodes([$languageFromStore]);
				Yii::$app->language = $entity->code;
				return;
			} catch(NotFoundHttpException $e) {}
		}
		$clientLanguages = Yii::$app->getRequest()->getAcceptableLanguages();
		try {
			$languageFromUserAgent = $this->repository->oneByLocalesOrCodes($clientLanguages);
			$this->repository->saveCurrent($languageFromUserAgent->code);
		} catch(NotFoundHttpException $e) {}
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
