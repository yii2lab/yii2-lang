<?php

namespace yii2module\lang\domain\interfaces\services;

use yii\web\NotFoundHttpException;
use yii2module\lang\domain\entities\LanguageEntity;

interface LanguageInterface {
	
	/**
	 * @return LanguageEntity
	 * @throws NotFoundHttpException
	 */
	public function oneCurrent();
	public function saveCurrent($language);
	
}
