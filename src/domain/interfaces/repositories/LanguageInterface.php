<?php

namespace yii2module\lang\domain\interfaces\repositories;

use yii\web\NotFoundHttpException;
use yii2module\lang\domain\entities\LanguageEntity;

interface LanguageInterface {
	
	/**
	 * @return LanguageEntity
	 * @throws NotFoundHttpException
	 */
	public function oneCurrent();
	public function saveCurrent($language);
	
	/**
	 * @return LanguageEntity
	 * @throws NotFoundHttpException
	 */
	public function oneByLocalesOrCodes($value);
	
}
