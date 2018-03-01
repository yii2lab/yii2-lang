<?php

namespace yii2module\lang\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;
use yii2lab\extension\menu\helpers\MenuHelper;
use yii2lab\misc\enums\HtmlEnum;

class LangSelector extends Widget {
	
	/**
	 * Runs the widget
	 */
	public function run() {
		$currentEntity = Yii::$app->lang->language->oneCurrent();
		echo Html::a( $currentEntity->title . HtmlEnum::CARET, '#', [
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
		$collection = Yii::$app->lang->language->all();
		foreach($collection as $entity) {
			$items[] = [
				'label' => $entity->title,
				'url' => 'lang/default/change?language=' . $entity->code,
				'linkOptions' => ['data-method' => 'post'],
			];
		}
		return MenuHelper::gen($items);
	}
}
