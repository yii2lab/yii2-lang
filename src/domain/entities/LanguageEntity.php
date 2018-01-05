<?php

namespace yii2module\lang\domain\entities;

use yii2lab\domain\BaseEntity;

/**
 * Class LanguageEntity
 *
 * @package yii2module\lang\domain\entities
 *
 * @property string $code
 * @property string $title
 * @property string $locale
 * @property boolean $is_main
 */
class LanguageEntity extends BaseEntity {
	
	protected $code;
	protected $title;
	protected $locale;
	protected $is_main = false;

	public function fieldType() {
		return [
			'is_main' => 'boolean',
		];
	}

	public function rules() {
		return [
			[['code', 'title', 'locale'], 'trim'],
			[['code', 'title', 'locale'], 'required'],
		];
	}

}