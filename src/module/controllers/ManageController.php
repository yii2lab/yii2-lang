<?php
namespace yii2module\lang\module\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii2lab\extension\web\helpers\Behavior;
use yii2module\lang\domain\enums\LangPermissionEnum;

class ManageController extends Controller
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			Behavior::access(LangPermissionEnum::MANAGE),
		];
	}

	public function actionIndex()
	{
		$dataProvider = new ArrayDataProvider([
			'allModels' => \App::$domain->lang->language->all(),
			'sort' => [
				'attributes' => ['title', 'code', 'locale', 'is_main'],
			],
		]);
		return $this->render('index', compact('dataProvider'));
	}
	
}

