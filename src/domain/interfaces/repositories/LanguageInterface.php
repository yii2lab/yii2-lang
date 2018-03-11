<?php

namespace yii2module\lang\domain\interfaces\repositories;

use yii\web\NotFoundHttpException;
use yii2module\lang\domain\entities\LanguageEntity;
use yii2lab\domain\interfaces\repositories\ReadInterface;

interface LanguageInterface extends ReadInterface {
	
	/**
	 * @return LanguageEntity
	 * @throws NotFoundHttpException
	 */
	public function oneCurrent();
	public function oneMain();
	public function saveCurrent($language);
	
	/**
	 * @return LanguageEntity
	 * @throws NotFoundHttpException
	 */
	public function oneByLocale($value);
	
}
