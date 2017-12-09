<?php

namespace yii2module\lang\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;
use yii2lab\helpers\MenuHelper;
use yii2module\lang\domain\helpers\LangHelper;

class LangSelector extends Widget {
	
	/**
	 * Runs the widget
	 */
	public function run() {
		echo Html::a(LangHelper::current() . '<b class="caret"></b>', '#', [
			'class' => 'dropdown-toggle',
			'data-toggle' => 'dropdown',
		]);
		echo Dropdown::widget([
			'items' => $this->collectionToMenu(),
		]);
	}
	
	public function getMenu() {
		return $this->collectionToMenu();
	}
	
	private function collectionToMenu() {
		$items = [];
		$collection = Yii::$app->lng->getAllLanguages();
		foreach($collection as $item) {
			$items[] = [
				'label' => $item['title'],
				'url' => 'lang/default/change?language=' . $item['code'],
				'linkOptions' => ['data-method' => 'post'],
			];
		}
		return MenuHelper::gen($items);
	}
}
