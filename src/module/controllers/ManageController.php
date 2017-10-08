<?php
namespace yii2module\lang\module\controllers;

use Yii;
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
		return $this->render('index');
	}
	
}

