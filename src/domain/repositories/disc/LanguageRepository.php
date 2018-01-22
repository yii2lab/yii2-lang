<?php

namespace yii2module\lang\domain\repositories\disc;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2lab\domain\data\Query;
use yii2lab\domain\interfaces\repositories\ReadInterface;
use yii2lab\domain\repositories\ActiveDiscRepository;
use yii2module\lang\domain\entities\LanguageEntity;
use yii2module\lang\domain\enums\LanguageEnum;
use yii2module\lang\domain\interfaces\repositories\LanguageInterface;

class LanguageRepository extends ActiveDiscRepository implements LanguageInterface, ReadInterface {
	
	public $table = 'languages';
	public $callback;
	
	protected $primaryKey = 'locale';
	
	/**
	 * @return LanguageEntity
	 * @throws NotFoundHttpException
	 */
	public function oneCurrent() {
		$entity = $this->oneByLocale(Yii::$app->language);
		return $entity;
	}
	
	public function saveCurrent($language) {
		try {
			$entity = $this->oneByLocale($language);
			$language = $entity->locale;
		} catch(NotFoundHttpException $e) {
			return;
		}
		Yii::$app->language = $language;
		$this->domain->repositories->store->set($language);
		if (is_callable($this->callback)) {
			call_user_func($this->callback);
		}
	}
	
	/**
	 * @param Query|null $query
	 *
	 * @return LanguageEntity[]
	 */
	public function all(Query $query = null) {
		$collection = parent::all($query);
		if(YII_ENV_TEST) {
			$collection[] = $this->forgeEntity([
				'title' => 'Source',
				'code' => LanguageEnum::code(LanguageEnum::SOURCE),
				'locale' => LanguageEnum::SOURCE,
				'is_main' => false,
			]);
		}
		return $collection;
	}
	
	/**
	 * @return LanguageEntity
	 * @throws NotFoundHttpException
	 */
	public function oneByLocale($locales) {
		$collection = $this->all();
		$locales = ArrayHelper::toArray($locales);
		foreach ($locales as $language) {
			$pattern = preg_quote(substr($language, 0, 2), '/');
			/** @var LanguageEntity $entity */
			foreach ($collection as $entity) {
				if (preg_match('/^' . $pattern . '/', $entity->locale)) {
					return $entity;
				}
			}
		}
		throw new NotFoundHttpException(static::class);
	}
	
}
