<?php

/**
 * @param       $category
 * @param       $message
 * @param array $params
 * @param null  $language
 *
 * @return string
 * @deprecated
 */
function t($category, $message, $params = [], $language = null) {
	return \Yii::t($category, $message, $params, $language);
}
