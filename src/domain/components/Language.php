<?php

namespace yii2module\lang\domain\components;

use Yii;
use yii\base\Component;

class Language extends Component
{

	public function init()
	{
		Yii::$app->lang->language->init();
		parent::init();
	}

}
