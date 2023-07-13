<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Evooy */

$this->title = Yii::t('admin', 'Update Field');
$this->params['breadcrumbs'] = [
    [
        'label' => ucfirst(Yii::$app->controller->id),
        'url' => ['index'],
    ],

];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Additional Field'), 'url' => ['af']];
$this->params['breadcrumbs'][] = Yii::t('admin', 'Update');
?>
<div class="evooy-update">

    <?= $this->render('@app/views/crud/_af_form', [
        'model' => $model,
        'tables' => $tables,
        'templates' => $templates,
        'all_fields_index' => $all_fields_index,
        'all_fields_update' => $all_fields_update,
    ]) ?>

</div>
