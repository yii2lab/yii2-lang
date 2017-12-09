<?php

namespace yii2module\lang\domain\repositories\disc;

use Yii;
use yii2lab\domain\data\Query;
use yii2lab\domain\repositories\ActiveDiscRepository;
use yii2module\lang\domain\helpers\LangHelper;

class LanguageRepository extends ActiveDiscRepository {
	
	public $table = 'languages';
	
	public function oneByCode($code) {
		$query = Query::forge();
		$query->where('code', $code);
		return $this->one($query);
	}
	
	public function currentLocale() {
		return Yii::$app->language;
	}
	
	public function currentLanguage() {
		return LangHelper::locale2lang(Yii::$app->language);
	}
	
	public function oneCurrent() {
		$currentLang = $this->currentLanguage();
		$entity = $this->oneByCode($currentLang);
		return $entity;
	}
	
}
