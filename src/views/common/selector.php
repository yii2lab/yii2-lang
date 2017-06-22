<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$allLanguages = ArrayHelper::map(Yii::$app->lng->getAllLanguages(), 'code', 'title');

$script = <<< JS
	selectLanguage = function(value) {
		var wrapper  = $("#langSelector");
		var form  = wrapper.find("form");
		var langInput = form.find("[name=language]");
		langInput.val(value);
		form.submit();
	}
JS;
$this->registerJs($script);

?>

<span id="langSelector">

	<?= Html::beginForm(['/lang/default/change'], 'post', ['id' => 'langSelectorForm' ,'class' => 'hidden']) ?>
	<?= Html::hiddenInput('language') ?>
	<?= Html::endForm() ?>

	<?= Html::beginTag('select', [
		//'name' => 'language', 
		'onchange' => 'selectLanguage(this.value)',
	]) ?>
	<?= Html::renderSelectOptions(Yii::$app->language, $allLanguages) ?>
	<?= Html::endTag('select') ?>

</span>
