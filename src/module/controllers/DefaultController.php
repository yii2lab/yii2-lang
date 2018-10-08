<?php

namespace yii2module\lang\module\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii2lab\extension\web\helpers\Behavior;

class DefaultController extends Controller
{
	
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verb' => Behavior::verb([
				'change' => ['post'],
			]),
		];
	}

	function actionChange($language) {
		$request = Yii::$app->request;
		if(!empty($language)) {
			\App::$domain->lang->language->saveCurrent($language);
			return $this->redirect($request->referrer);
		}
	}
	
}
