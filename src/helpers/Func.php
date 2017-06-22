<?php

use yii2lab\lang\helpers\LangHelper;

function t($category, $message, $params = [], $language = null) {
	/* if(YII_ENV_TEST) {
		return $category . ':' . $message;
	} */
	$categoryParts = explode('/', $category);
	if(count($categoryParts) > 1) {
		return LangHelper::module($category, $message, $params, $language);
	}
	return \Yii::t($category, $message, $params, $language);
}
