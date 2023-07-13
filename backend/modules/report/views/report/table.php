<?php

use yii\helpers\Html;
use backend\components\ActionColumn;
use kartik\icons\Icon;
Icon::map($this, Icon::FA);

//define default variables
$panelBefore = '';
$panelHeading = '';

if(isset($title) && false !== $title) {
	$this->title = ucfirst(Yii::$app->controller->id);
	$this->params['breadcrumbs'][] = $this->title;
	?>
	<h1><?= Html::encode($this->title) ?></h1>
	<?php
	$panelHeading = '{panelHeading}';
}
Yii::$app->trigger('frontend/crud/index/after/title');

if(isset($button) && false !== $button) {
	$af_columns[] = [
		'class' => ActionColumn::className(),
		'options' => [
			'width' => '100px',
		],
		'buttons' => [
			[
				'url' => 'update',
				'icon' => 'pencil',
				'class' => 'btn-default',
				'label' => Yii::t('app', 'Edit'),

			],
			[
				'url' => 'delete',
				'icon' => 'trash-o',
				'class' => 'btn-danger',
				'data-method' => 'post',
				'label' => Yii::t('app', 'Delete'),
			],
		],
	];
	$panelBefore = '{panelBefore}';
}
$panelTemplate = "<div class='panel {type}'>
                    {$panelHeading}
                    <div class='panel-body'>
                        {$panelBefore}
                        {items}
                    </div>
                    {panelFooter}
                </div>";

if(count($af_columns) == 0 )
	return;

$table = isset($table)?$table:[];

$default = [
	'columns' => $af_columns,
	'options' => [
		'id' => isset($id)?$id:'table-grid',
	],
	'dataProvider' => $dataProvider,
	'responsive' => true,
	'striped' => false,
	'hover' => true,
	'bordered' => false,
	'condensed' => true,
	'tableOptions' => ['width' => '100%'],
	'panel' => [
		'heading'=>false,
		'type'=>'success',
		'before'=>false,
		'after'=>false,
		'footer'=>false
	],

];
echo \kartik\grid\GridView::widget(array_replace_recursive($default,$table));