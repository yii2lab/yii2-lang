<?php

namespace yii2module\lang\module\helpers;

use common\enums\rbac\PermissionEnum;

class Menu {
	
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
