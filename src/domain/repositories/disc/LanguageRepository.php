<?php

namespace yii2module\lang\domain\repositories\disc;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2lab\domain\data\Query;
use yii2lab\domain\repositories\ActiveDiscRepository;
use yii2module\lang\domain\enums\LanguageEnum;
use yii2module\lang\domain\helpers\LangHelper;

class LanguageRepository extends ActiveDiscRepository {
	
	public $table = 'languages';
	public $callback;
	
	public function oneCurrent() {
		$currentLang = $this->currentLanguage();
		$entity = $this->oneByCode($currentLang);
		return $entity;
	}
	
	public function saveCurrent($language) {
		$language = $this->filterLanguage($language);
		Yii::$app->language = $language;
		$this->domain->repositories->store->set($language);
		if (is_callable($this->callback)) {
			call_user_func($this->callback);
		}
	}
	
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
	
	public function initLanguage()
	{
		$languageFromStore = $this->domain->repositories->store->get();
		if (!empty($languageFromStore) && $this->isExistsByCode($languageFromStore)) {
			Yii::$app->language = $languageFromStore;
		} else {
			$clientLanguages = Yii::$app->getRequest()->getAcceptableLanguages();
			try {
				$languageFromUserAgent = $this->oneByLocalesOrCodes($clientLanguages);
				$this->saveCurrent($languageFromUserAgent->locale);
			} catch(NotFoundHttpException $e) {}
		}
	}
	
	private function isExistsByCode($code) {
		$query = Query::forge();
		$query->where('code', $code);
		return $this->isExists($query);
	}
	
	private function oneMain() {
		$query = Query::forge();
		$query->where('is_main', 1);
		return $this->one($query);
	}
	
	private function oneByCode($code) {
		$query = Query::forge();
		$query->where('code', $code);
		return $this->one($query);
	}
	
	private function currentLanguage() {
		return LangHelper::locale2lang(Yii::$app->language);
	}
	
	private function oneByLocale($locales) {
		$collection = $this->all();
		$locales = ArrayHelper::toArray($locales);
		foreach ($locales as $language) {
			$pattern = preg_quote(substr($language, 0, 2), '/');
			foreach ($collection as $entity) {
				if (preg_match('/^' . $pattern . '/', $entity->locale)) {
					return $entity;
				}
			}
		}
		throw new NotFoundHttpException(static::class);
	}
	
	private function oneByLocalesOrCodes($value) {
		try {
			return $this->oneByCode($value);
		} catch(NotFoundHttpException $e) {}
		return $this->oneByLocale($value);
	}
	
	private function filterLanguage($language = null)
	{
		if (!empty($language) && $this->isExistsByCode($language)) {
			return $language;
		}
		return $this->oneMain();
	}
	
}
