<?php

namespace tests\unit\services;

use Codeception\Test\Unit;
use Yii;
use yii2lab\domain\data\Query;

class LanguageTest extends Unit
{
	
	public function testTranslate()
	{
		$translate = Yii::t('action', 'send');
		expect('Отправить')->equals($translate);
	}
	
	public function testSwitchLang()
	{
		Yii::$app->lang->language->saveCurrent('en');
		expect('en-UK')->equals(Yii::$app->language);
		
		$entity = Yii::$app->lang->language->oneCurrent();
		$this->tester->assertEntity([
			'code' => 'en',
			'locale' => 'en-UK',
			'is_main' => false,
		], $entity);
		
		Yii::$app->lang->language->saveCurrent('ru');
		expect('ru-RU')->equals(Yii::$app->language);
		$entity = Yii::$app->lang->language->oneCurrent();
		$this->tester->assertEntity([
			'code' => 'ru',
			'locale' => 'ru-RU',
			'is_main' => true,
		], $entity);
	}
	
	public function testSwitchInvalidLang()
	{
		Yii::$app->lang->language->saveCurrent('ruu');
		expect('xx-XX')->equals(Yii::$app->language);
		$entity = Yii::$app->lang->language->oneCurrent();
		$this->tester->assertEntity([
			'code' => 'xx',
			'locale' => 'xx-XX',
			'is_main' => false,
		], $entity);
	}
	
	public function testLangList()
	{
		$collection = Yii::$app->lang->language->all();
		$this->tester->assertCollection([
			[
				'code' => 'ru',
				'locale' => 'ru-RU',
				'is_main' => true,
			],
			[
				'code' => 'en',
				'locale' => 'en-UK',
				'is_main' => false,
			],
			[
				'code' => 'xx',
				'locale' => 'xx-XX',
				'is_main' => false,
			],
		], $collection);
	}
	
	public function testLangListOrderByCode()
	{
		$query = Query::forge();
		$query->orderBy('code');
		$collection = Yii::$app->lang->language->all($query);
		$this->tester->assertCollection([
			[
				'code' => 'en',
				'locale' => 'en-UK',
				'is_main' => false,
			],
			[
				'code' => 'ru',
				'locale' => 'ru-RU',
				'is_main' => true,
			],
			[
				'code' => 'xx',
				'locale' => 'xx-XX',
				'is_main' => false,
			],
		], $collection);
	}
	
}
