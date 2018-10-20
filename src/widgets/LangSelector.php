<?php

namespace yii2module\lang\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;
use yii2lab\extension\menu\helpers\MenuHelper;
use yii2lab\extension\web\enums\HtmlEnum;

class LangSelector extends Widget {
	
	/**
	 * Runs the widget
	 */

	public function run() {
	    $currentEntity = \App::$domain->lang->language->oneCurrent();
        $view = $this->getView();
        LangSelectorAsset::register($view);
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
	
	protected function collectionToMenu() {
		$items = [];
		$collection = \App::$domain->lang->language->all();
		//$currentEntity = \App::$domain->lang->language->oneCurrent();
		foreach($collection as $entity) {
			$items[] = [
				'label' => $entity->title,
				'url' => 'lang/default/change?language=' . $entity->code,
				'linkOptions' => ['data-method' => 'post'],
				//'active' => ($entity->code == $currentEntity->code),
			];
		}
		return MenuHelper::gen($items);
	}
}
