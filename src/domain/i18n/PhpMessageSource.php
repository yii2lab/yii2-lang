<?php

namespace yii2module\lang\domain\i18n;

use yii\i18n\MessageSource;
use yii2module\lang\domain\behaviors\MissingTranslationBehavior;
use yii2module\lang\domain\enums\LanguageEnum;

class PhpMessageSource extends \yii\i18n\PhpMessageSource
{
 
	public $forceTranslation = true;
	public $sourceLanguage = LanguageEnum::SOURCE;

	public function behaviors()
    {
        return [
            'missingTranslation' => MissingTranslationBehavior::class,
        ];
    }

}
