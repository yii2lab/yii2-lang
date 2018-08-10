<?php
namespace yii2module\lang\module\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii2lab\helpers\Behavior;

class ManageController extends Controller
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			Behavior::access(PermissionEnum::LANG_MANAGE),
		];
	}

	public function actionIndex()
	{
		$dataProvider = new ArrayDataProvider([
			'allModels' => Yii::$domain->lang->language->all(),
			'sort' => [
				'attributes' => ['title', 'code', 'locale', 'is_main'],
			],
		]);
		return $this->render('index', compact('dataProvider'));
	}
	
}

