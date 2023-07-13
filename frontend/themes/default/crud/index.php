<?php

use yii\helpers\Html;
use backend\components\ActionColumn;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
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

                </div>";

if(count($af_columns) == 0 )
    return;

echo DynaGrid::widget([
    'columns' => $af_columns,
    'options' => [
        'id' => 'evooy-grid',
    ],
    'storage'=>'cookie',
    'showPersonalize'=>true,
    'theme' => 'simple-bordered',
    'userSpecific' => true,
    'gridOptions'=>[
        'dataProvider' => $dataProvider,
        'hover' => true,
        'bordered' => false,
        'condensed' => true,
        'panelTemplate' => $panelTemplate,
        'panelHeadingTemplate' => '
                <h3 class="panel-title">
                    {heading}
                </h3>
                <div class="tools pull-right">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="javascript:;" class="remove"></a>
                    </div>
                <div class="clearfix"></div>
                ',
        'panel' => [
            'heading' =>  $this->title,
        ],
        'toolbar' =>  [
            ['content'=> Html::a('<i class="glyphicon glyphicon-plus"></i>', ['update'] , ['data-pjax'=>0, 'class' => 'btn btn-primary', 'title'=>Yii::t('app','Add') ])],
        ]
    ]
]);
