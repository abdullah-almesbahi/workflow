<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Evooy */

?>


    <?=  GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 100,
            ]
        ]),
        'columns' => [
            [
                'label' => 'User',
                'value' => function($model, $index, $widget){
                    return $model->user ? $model->user->username : "";
                }
            ],
            'action',
            [
                'attribute' => 'field',
                'label' => 'field',
                'value' => function($model, $index, $widget){
                    return $model->getParent()->getAttributeLabel($model->field);
                }
            ],
            'old_value',
            'new_value',
            [
                'attribute' => 'stamp',
                'label' => 'Date Changed',
                'value' => function($model, $index, $widget){
                    return date("d-m-Y H:i:s", strtotime($model->stamp));
                }
            ]
        ]
    ]); ?>


