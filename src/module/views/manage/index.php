<?php

use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View
 * @var $dataProvider ArrayDataProvider
 */

$this->title = t('lang/manage', 'title');

?>

<div class="box box-primary">
	<div class="box-body">
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'layout' => '{summary}{items}{pager}',
			'columns' => [
				[
					'attribute' => 'title',
					'label' => t('lang/main', 'language'),
				],
				[
					'attribute' => 'code',
					'label' => t('lang/main', 'code'),
				],
				[
					'attribute' => 'locale',
					'label' => t('lang/main', 'locale'),
				],
				[
					'attribute' => 'is_main',
					'label' => t('lang/main', 'main_as_default'),
					'format' => 'html',
					'value' => function ($entity) {
						return  $entity->is_main ? '<span class="label label-success"><i class="fa fa-check"></i> '.t('yii', 'Yes').'</span>' : '<span class="label label-danger"><i class="fa fa-times"></i> '.t('yii', 'No').'</span>';
					},
				],
			],
		]); ?>
	</div>
</div>
