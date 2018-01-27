<?php

namespace yii2module\lang\domain\entities;

use yii2lab\domain\BaseEntity;

/**
 * Class LanguageEntity
 *
 * @package yii2module\lang\domain\entities
 *
 * @property string $code
 * @property string $name
 * @property string $title
 * @property string $locale
 * @property boolean $is_main
 * @property boolean $is_enabled
 */
class LanguageEntity extends BaseEntity {
	
	protected $code;
	protected $name;
	protected $title;
	protected $locale;
	protected $is_main = false;
	protected $is_enabled = false;
	
	public function fieldType() {
		return [
			'is_main' => 'boolean',
			'is_enabled' => 'boolean',
		];
	}

	public function rules() {
		return [
			[['code', 'title', 'name', 'locale'], 'trim'],
			[['code', 'title', 'name', 'locale'], 'required'],
		];
	}

}