<?php

namespace yii2module\lang\module\helpers;

// todo: отрефакторить - сделать нормальный интерфейс и родителя

use common\enums\rbac\PermissionEnum;

class Menu {
	
	static function getMenu() {
		return [
			'label' => ['lang/main', 'title'],
			'url' => 'lang/manage',
			'module' => 'lang',
			//'icon' => 'language',
			'access' => PermissionEnum::LANG_MANAGE,
		];
	}

}
