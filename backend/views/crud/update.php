<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Evooy */

$this->title = Yii::t('admin', '{action} {modelClass} ', [
    'action' => $model->isNewRecord ? Yii::t('admin', 'Add') :  Yii::t('admin', 'Update'),
    'modelClass' => ucfirst(Yii::$app->controller->id),
]) . ' ' . $model->title;
$this->params['breadcrumbs'] = [
    [
        'label' => ucfirst(Yii::$app->controller->id),
        'url' => ['index'],
    ],

];
$this->params['breadcrumbs'][] = $model->isNewRecord ? Yii::t('admin', 'Add') :  Yii::t('admin', 'Update');

?>
<div class="evooy-update">

    <?= $this->render('_form', [
        'fields' => $fields,
        'model' => $model,
    ]) ?>

</div>
