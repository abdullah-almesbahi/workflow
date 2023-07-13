<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use backend\components\ActionColumn;

/* @var $this yii\web\View */
$this->title = 'My Account';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('tabs', [

    ]) ?>

    <?= $this->render('@app/themes/default/crud-table', [
        'dataProvider' => $dataProvider,
        'af_columns' => $af_columns,
    ]) ?>


    <a href="<?= \yii\helpers\Url::to(['address-update']);  ?>" class="btn btn-primary">Add new address</a>

</div>