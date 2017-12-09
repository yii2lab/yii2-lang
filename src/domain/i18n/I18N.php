<?php

namespace yii2module\lang\domain\i18n;

use Yii;
use yii2module\lang\domain\helpers\LangHelper;

class I18N extends \yii\i18n\I18N
{
 
	public function translate($category, $message, $params, $language) {
		$categoryParts = explode('/', $category);
		if(count($categoryParts) > 1) {
			$nameArr = explode('/', $category);
			$moduleName = $nameArr[0];
			$fileName = isset($nameArr[1]) ? $nameArr[1] : 'main';
			// todo: заменить this на реальные имена
			if($moduleName == 'this' || empty($moduleName)) {
				$moduleName = Yii::$app->controller->module->id;
			}
			$pathName = 'modules/' . $moduleName;
			if(empty($this->translations[ $pathName . '/*' ])) {
				LangHelper::registerModule($moduleName);
			}
			$category = $pathName . '/' . $fileName;
		}
		return parent::translate($category, $message, $params, $language);
	}
 
}
