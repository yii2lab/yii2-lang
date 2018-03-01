<?php

namespace tests\unit\services;

use Codeception\Test\Unit;
use Yii;
use yii2lab\domain\data\Query;
use yii2module\lang\domain\enums\LanguageEnum;

class LanguageTest extends Unit
{
	
	public function testTranslate()
	{
		$translate = Yii::t('action', 'send');
		expect('Отправить')->equals($translate);
	}
	
}
