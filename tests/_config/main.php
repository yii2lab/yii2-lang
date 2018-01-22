<?php

use yii2module\lang\domain\enums\LanguageEnum;

$config = require(ROOT_DIR . '/vendor/yii2lab/yii2-app/tests/store/app/common/config/main.php');

return \yii\helpers\ArrayHelper::merge($config, [
	'language' => LanguageEnum::RU, // current Language
]);