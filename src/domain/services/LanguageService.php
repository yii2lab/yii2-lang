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
	
	public function initCurrent() {
		if(APP == CONSOLE) {
			return;
		}
        $entity = $this->getLanguageFromStore();
		if(empty($entity)) {
            $entity = $this->setLanguageFromClient();
        }
        if(empty($entity)) {
            $entity = $this->repository->oneMain();
        }
        $this->saveCurrent($entity);
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
	
	public function oneByLocale($locale) {
		return $this->repository->oneByLocale($locale);
	}

	private function getLanguageFromStore() {
        $languageFromStore = $this->domain->repositories->store->get();
        if (empty($languageFromStore)) {
            return null;
        }
        try {
            return $this->repository->oneByLocale([$languageFromStore]);
        } catch(NotFoundHttpException $e) {
            return null;
        }
    }

    private function setLanguageFromClient() {
        $clientLanguages = Yii::$app->getRequest()->getAcceptableLanguages();
        try {
            return $this->repository->oneByLocale($clientLanguages);
        } catch(NotFoundHttpException $e) {
            return null;
        }
    }

}
