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
	
	public function testCurrent()
	{
		expect(LanguageEnum::RU)->equals(Yii::$app->language);
		
		$entity = Yii::$app->lang->language->oneCurrent();
		$this->tester->assertEntity([
			'code' => 'ru',
			'locale' => LanguageEnum::RU,
			'is_main' => true,
		], $entity);
	}
	
	public function testSwitchLang()
	{
		Yii::$app->lang->language->saveCurrent('en');
		expect('en')->equals(Yii::$app->language);
		
		Yii::$app->lang->language->saveCurrent('ru');
		expect('ru')->equals(Yii::$app->language);
	}
	
	public function testSwitchInvalidLang()
	{
		Yii::$app->lang->language->saveCurrent('zx');
		expect(LanguageEnum::RU)->equals(Yii::$app->language);
	}
	
	public function testList()
	{
		$collection = Yii::$app->lang->language->all();
		$this->tester->assertCollection([
			[
				'code' => 'ru',
				'locale' => LanguageEnum::RU,
				'is_main' => true,
			],
			[
				'code' => 'en',
				'locale' => LanguageEnum::EN,
				'is_main' => false,
			],
			[
				'code' => 'xx',
				'locale' => LanguageEnum::SOURCE,
				'is_main' => false,
			],
		], $collection);
	}
	
	public function testListOrderByCode()
	{
		$query = Query::forge();
		$query->orderBy('code');
		$collection = Yii::$app->lang->language->all($query);
		$this->tester->assertCollection([
			[
				'code' => 'en',
				'locale' => LanguageEnum::EN,
				'is_main' => false,
			],
			[
				'code' => 'ru',
				'locale' => LanguageEnum::RU,
				'is_main' => true,
			],
			[
				'code' => 'xx',
				'locale' => LanguageEnum::SOURCE,
				'is_main' => false,
			],
		], $collection);
	}
	
	public function testOneByLocale()
	{
		$expectEntity = [
			'code' => 'ru',
			'locale' => LanguageEnum::RU,
			'is_main' => true,
		];
		
		$collection = Yii::$app->lang->language->oneByLocale('ru-RU');
		$this->tester->assertEntity($expectEntity, $collection);
		
		$collection = Yii::$app->lang->language->oneByLocale('ru');
		$this->tester->assertEntity($expectEntity, $collection);
	}
	
}
