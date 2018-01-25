<?php

use yii2module\lang\domain\enums\LanguageEnum;

$config = require(ROOT_DIR . DS . TEST_APPLICATION_DIR .  DS . 'common/config/main.php');

return \yii\helpers\ArrayHelper::merge($config, [
	'language' => LanguageEnum::RU, // current Language
	'bootstrap' => ['log', 'language', 'queue'],
	'components' => [
		'language' => 'yii2module\lang\domain\components\Language',
	],
]);