<?php

namespace yii2module\lang\domain\repositories\filedb;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\repositories\ActiveFiledbRepository;
use yii2module\lang\domain\entities\LanguageEntity;
use yii2module\lang\domain\enums\LanguageEnum;
use yii2module\lang\domain\interfaces\repositories\LanguageInterface;

class LanguageRepository extends ActiveFiledbRepository implements LanguageInterface {
	
	public function tableName()
	{
		return 'languages';
	}
	
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
		if(!($language instanceof BaseEntity)) {
			try {
				$language = $this->oneByLocale($language);
			} catch(NotFoundHttpException $e) {
				return;
			}
		}
		Yii::$app->language = $language->code;
		$this->domain->repositories->store->set($language->locale);
		if (is_callable($this->callback)) {
			call_user_func($this->callback);
		}
	}
	
	public function oneMain() {
		$query = Query::forge();
		$query->where('is_main', true);
		return $this->one($query);
	}
	
	/**
	 * @param Query|null $query
	 *
	 * @return LanguageEntity[]
	 */
	public function all(Query $query = null) {
		$query = Query::forge($query);
		$collection = parent::all($query);
		if(YII_ENV_TEST) {
			$collection[] = $this->forgeEntity([
				'title' => 'Source',
				'name' => 'source',
				'code' => LanguageEnum::code(LanguageEnum::SOURCE),
				'locale' => LanguageEnum::SOURCE,
				'is_main' => false,
				'is_enabled' => true,
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
		foreach ($locales as $locale) {
			$entity = $this->searchByLocale($collection, $locale);
			if($entity instanceof LanguageEntity) {
				return $entity;
			}
		}
		return $this->oneMain();
	}
	
	private function searchByLocale($collection, $locale) {
		$pattern = preg_quote(substr($locale, 0, 2), '/');
		/** @var LanguageEntity $entity */
		foreach ($collection as $entity) {
			if (preg_match('/^' . $pattern . '/', $entity->locale)) {
				return $entity;
			}
		}
		return null;
	}
	
}
