<?php

namespace yii2module\lang\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

class DefaultController extends Controller
{
	
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'change' => ['post'],
				],
			],
		];
	}

	function actionChange($language) {
		$request = Yii::$app->request;
		if(!empty($language)) {
			Yii::$app->lng->saveLanguage($language);
			return $this->redirect($request->referrer);
		}
	}
	
}
