<?php

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \backend\components\SearchModel
 * @var $this \yii\web\View
 */

use backend\components\ActionColumn;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use kartik\helpers\Html;
use kartik\icons\Icon;

$this->title = Yii::t('admin', 'Users');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">
    <?=
        DynaGrid::widget([
            'columns' => [
                [
                    'class' => \kartik\grid\CheckboxColumn::className(),
                    'options' => [
                        'width' => '10px',
                    ],
                ],
                [
                    'attribute' => 'id',
                    'options' => [
                        'width' => '80px',
                    ],
                ],

                'username',
                'email:email',
                [
                    'attribute' => 'status',
                    'filter' => common\models\User::getStatuses(),
                ],
                [
                    'attribute'=>'create_time',
                    'filterType'=>GridView::FILTER_DATETIME ,
                    //'format'=>'raw',
                    'width'=>'220px',
//                    'filterWidgetOptions'=>[
//                        //'pluginOptions'=>['format'=>'yyyy-mm-dd']
//                    ],
                    //'visible'=>true,
                ],
                //'create_time:datetime',
                [
                    'class' => ActionColumn::className(),
                    'options' => [
                        'width' => '100px',
                    ],
                    'buttons' => [
                        [
                            'url' => 'update',
                            'icon' => 'pencil',
                            'class' => 'btn-default',
                            'label' => Yii::t('admin', 'Edit'),

                        ],
                        [
                            'url' => 'delete',
                            'icon' => 'trash-o',
                            'class' => 'btn-danger',
                            'label' => Yii::t('admin', 'Delete'),
                        ],
                    ],
                ],
            ],
            'options' => [
                'id' => 'users-grid',
            ],
            'storage'=>'cookie',
            'showPersonalize'=>true,
            'theme' => 'simple-bordered',
            'gridOptions'=>[
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'hover' => true,
                'bordered' => false,
                'condensed' => true,
                //'pjax'=>true,
                'panelTemplate' => '<div class="panel {type}">
                    {panelHeading}
                    <div class="panel-body">
                        {panelBefore}
                        {items}
                        {panelAfter}
                    </div>
                    {panelFooter}
                </div>',
                'panelHeadingTemplate' => '
                <h3 class="panel-title">
                    {heading}
                </h3>
                <div class="tools pull-right">
                    <a href="javascript:;" class="collapse"></a>
                    {dynagrid}
                    <a href="javascript:;" class="remove"></a>
                    </div>
                <div class="clearfix"></div>
                ',
                'panel' => [
                    'heading' =>  $this->title,
                    'after' =>  \backend\widgets\RemoveAllButton::widget([
                        'url' => '/backend/user/remove-all',
                        'gridSelector' => '.grid-view',
                        'htmlOptions' => [
                            'class' => 'btn btn-danger'
                        ],
                    ]).'<div class="pull-right">{summary}</div>',

                ],
                'toolbar' =>  [
                    ['content'=> Html::a('<i class="glyphicon glyphicon-plus"></i>', ['update'] , ['data-pjax'=>0, 'class' => 'btn btn-primary', 'title'=>Yii::t('admin','Add') ])],
                    ['content'=>'{dynagridFilter}{dynagridSort}'],
                    '{export}',
                ]
            ]
        ]);
    ?>
</div>