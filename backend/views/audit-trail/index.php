<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use \kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\ActionLogSearch $searchModel
 */

$this->title = Yii::t('admin', 'Audit Trail');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-log-index">

    <?php
    \backend\widgets\BackendWidget::begin(
        [
            'icon' => 'user',
            'title' => Html::encode($this->title),
        ]
    );
    ?>
    <?= GridView::widget([
        //        'dataProvider' => new ActiveDataProvider([
        //            'query' => $criteria,
        //            'pagination' => [
        //                'pageSize' => 100,
        //            ]
        //        ]),
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => 'User',
                'value' => function ($model, $index, $widget) {
                    return $model->user ? $model->user->username : "";
                }
            ],
            [
                'attribute' => 'model',
                'value' => function ($model, $index, $widget) {
                    $p = explode('\\', $model->model);
                    return end($p);
                }
            ],
            'model_id',
            'action',
            [
                'attribute' => 'field',
                'label' => 'field',
                'value' => function ($model, $index, $widget) {
                    return $model->getParent()->getAttributeLabel($model->field);
                }
            ],
            'old_value',
            'new_value',
            [
                'attribute' => 'stamp',
                'width' => '220px',
                'label' => 'Date Changed',
                //                'format'=>'date',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ],
                'value' => function ($model, $index, $widget) {
                    return \common\lib\Formatter::getDate($model->stamp);
                }
            ]
        ]
    ]); ?>
    <?php \backend\widgets\BackendWidget::end(); ?>
</div>