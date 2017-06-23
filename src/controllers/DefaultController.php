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

	function actionChange() {
		$request = Yii::$app->request;
		$body = $request->getBodyParams();
		if(!empty($body['language'])) {
			Yii::$app->lng->saveLanguage($body['language']);
			return $this->redirect($request->referrer);
		}
	}
	
}
