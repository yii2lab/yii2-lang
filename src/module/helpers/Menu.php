<?php

namespace yii2module\lang\module\helpers;

use yii2lab\extension\menu\interfaces\MenuInterface;
use yii2module\lang\domain\enums\LangPermissionEnum;

class Menu implements MenuInterface {
	
	public function toArray() {
		return [
			'label' => ['lang/main', 'title'],
			'url' => 'lang/manage',
			'module' => 'lang',
			//'icon' => 'language',
			'access' => LangPermissionEnum::MANAGE,
		];
	}

}
