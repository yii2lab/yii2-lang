<?php
namespace yii2module\lang\module\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii2lab\app\helpers\Config;

class ManageController extends Controller
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			Config::genAccess('lang.manage'),
		];
	}

	public function actionIndex()
	{
		$dataProvider = new ArrayDataProvider([
			'allModels' => Yii::$app->lang->language->all(),
			'sort' => [
				'attributes' => ['title', 'code', 'locale', 'is_main'],
			],
		]);
		return $this->render('index', compact('dataProvider'));
	}
	
}

