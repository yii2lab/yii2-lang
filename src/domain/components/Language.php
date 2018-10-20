<?php

namespace yii2module\lang\domain\components;

use yii\base\Component;
use yii2lab\domain\helpers\DomainHelper;

class Language extends Component
{

	public function init()
	{
		DomainHelper::forgeDomains([
			'lang' => 'yii2module\lang\domain\Domain',
		]);
		\App::$domain->lang->language->initCurrent();
		parent::init();
	}

}
