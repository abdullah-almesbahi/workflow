<?php

/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ArrayDataProvider
 */

use backend\components\ActionColumn;
use backend\widgets\BackendWidget;
use kartik\grid\GridView;
use kartik\helpers\Html;
use kartik\icons\Icon;
use yii\widgets\Pjax;
use yii\helpers\Url;

$this->title = Yii::t('admin', 'I18n');
$this->params['breadcrumbs'] = [
    $this->title,
];

?>
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <?php
        BackendWidget::begin(
            [
                'icon' => 'language',
                'title'=> $this->title,
                'footer' => Html::submitButton(
                    Icon::show('save') . Yii::t('admin', 'Save'),
                    ['class' => 'btn btn-primary']
                ),
            ]
        );
    ?>
        <?php
            Pjax::begin();
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'label' => Yii::t('admin', 'Alias'),
                        'value' => function($model, $key, $index, $column) {
                            return $key;
                        },
                    ],
                    [
                        'label' => Yii::t('admin', 'Local file'),
                        'value' => function($model, $key, $index, $column) {
                            return $model;
                        },
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'options' => [
                            'width' => '50px',
                        ],
                        //TODO : remove extra button (view and delete)
                        'buttons' => [
                            [
                                'url' => 'i18n/update',
                                'icon' => 'pencil',
                                'class' => 'btn-primary',
                                'label' => Yii::t('admin', 'Edit'),
                            ],
                        ],
                    ],
                ],
            ]);
            Pjax::end();
        ?>
    <?php BackendWidget::end(); ?>
</div>
</div>