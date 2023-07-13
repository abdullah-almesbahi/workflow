<?php


use kartik\dynagrid\DynaGrid;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\ActionLogSearch $searchModel
 */

$this->title = Yii::t('admin', 'Zone Reports');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-log-index">


    <?= DynaGrid::widget([
        'showPersonalize' => true,
        'theme' => 'simple-bordered',
        'userSpecific' => true,
        'storage' => 'cookie',
        'options' => [
            'id' =>  '2134-grid',
        ],
        'gridOptions' => [
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
                'after' =>   '<div class="pull-right">{summary}</div>',

            ],
            'toolbar' =>  [
                ['content' => '{dynagridFilter}{dynagridSort}'],
                '{export}',
            ],
            'export' => [
                'encoding' => 'utf-8'
            ],
        ],
        'columns' => [
            'title',
            [
                'attribute' => 'id',
                'label' => 'Total Requests',
                'value' => function ($model, $index, $widget) {
                    return "4";
                }
            ],
            [
                'attribute' => 'id',
                'label' => 'Pending Requests',
                'value' => function ($model, $index, $widget) {
                    return "6";
                }
            ],
            [
                'attribute' => 'id',
                'label' => 'Ongoing Requests',
                'value' => function ($model, $index, $widget) {
                    return "1";
                }
            ],
            [
                'attribute' => 'id',
                'label' => 'Completed Requests',
                'value' => function ($model, $index, $widget) {
                    return "5";
                }
            ],
            [
                'attribute' => 'id',
                'label' => 'Zone Requests %',
                'value' => function ($model, $index, $widget) {
                    return "2%";
                }
            ],
            // [
            //     'attribute' => 'user_id',
            //     'label' => 'User',
            //     'value' => function ($model, $index, $widget) {
            //         return $model->user ? $model->user->username : "";
            //     }
            // ],
            // [
            //     'attribute' => 'model',
            //     'value' => function ($model, $index, $widget) {
            //         $p = explode('\\', $model->model);
            //         return end($p);
            //     }
            // ],
            // 'model_id',
            // 'action',
            // [
            //     'attribute' => 'field',
            //     'label' => 'field',
            //     'value' => function ($model, $index, $widget) {
            //         return $model->field;
            //         //return $model->getParent()->getAttributeLabel($model->field);
            //     }
            // ],
            // 'old_value',
            // 'new_value',
            // [
            //     'attribute' => 'stamp',
            //     'width' => '220px',
            //     'label' => 'Date Changed',
            //     //                'format'=>'date',
            //     'filterType' => GridView::FILTER_DATE,
            //     'filterWidgetOptions' => [
            //         'pluginOptions' => [
            //             'format' => 'yyyy-mm-dd',
            //             'autoclose' => true,
            //             'todayHighlight' => true,
            //         ]
            //     ],
            //     'value' => function ($model, $index, $widget) {
            //         return \common\lib\Formatter::getDate($model->stamp);
            //     }
            // ]
        ]
    ]); ?>

</div>