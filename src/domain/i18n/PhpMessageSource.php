<?php

namespace yii2module\lang\domain\i18n;

use yii2module\lang\domain\enums\LanguageEnum;

class PhpMessageSource extends \yii\i18n\PhpMessageSource
{
 
	public $forceTranslation = true;
	public $sourceLanguage = LanguageEnum::SOURCE;
	
}
