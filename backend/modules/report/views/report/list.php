<?php

$this->title = Yii::t('admin', 'Reports');
$this->params['breadcrumbs'][] = $this->title;

?>

<div id="w7" class="grid-view" data-krajee-grid="kvGridInit_7156b031">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?= \kartik\helpers\Html::encode($this->title) ?>
            </h3>
        </div>
        <div class="panel-body">
            <?= $this->render('table', [
                'dataProvider' => $dataProvider,
                'af_columns' => $af_columns,
            ]); ?>
        </div>

    </div>
</div>