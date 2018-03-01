<?php

namespace yii2module\lang\module\helpers;

use common\enums\rbac\PermissionEnum;
use yii2lab\extension\menu\interfaces\MenuInterface;

class Menu implements MenuInterface {
	
	public function toArray() {
		return [
			'label' => ['lang/main', 'title'],
			'url' => 'lang/manage',
			'module' => 'lang',
			//'icon' => 'language',
			'access' => PermissionEnum::LANG_MANAGE,
		];
	}

}
