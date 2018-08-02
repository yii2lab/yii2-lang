<?php

namespace tests\unit\services;

use yii2lab\test\Test\Unit;
use Yii;

class LanguageTest extends Unit
{
	
	public function testTranslate()
	{
		$translate = Yii::t('action', 'send');
		$this->tester->assertEquals('Отправить', $translate);
	}
	
}
