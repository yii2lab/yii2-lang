<?php

namespace yii2module\lang\domain\i18n;

use yii2module\lang\domain\helpers\LangHelper;

class I18N extends \yii\i18n\I18N
{
 
	public function translate($category, $message, $params, $language) {
		$category = LangHelper::register($category);
		return parent::translate($category, $message, $params, $language);
	}
 
}
