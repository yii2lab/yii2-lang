<?php

use yii\bootstrap\Dropdown;
use yii\helpers\Html;
use yii2module\lang\helpers\LangHelper;

?>

<span class="dropup">
	<?= Html::a(
		LangHelper::current() . '<b class="caret"></b>',
		'#',
		[
			'class' => 'dropdown-toggle',
			'data-toggle' => 'dropdown',
		]
	) ?>
	<?= Dropdown::widget([
		'items' => LangHelper::allForMenu(),
	]) ?>
</span>
