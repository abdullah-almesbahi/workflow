<?php

use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;


?>

<div id="w7" class="grid-view" data-krajee-grid="kvGridInit_7156b031">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?= \kartik\helpers\Html::encode($this->title) ?>
            </h3>
        </div>
        <div class="panel-body" style="width: 50%">
            <?php if($model->description != '') echo $model->description . '<br/>'; ?>
            <?php $form = ActiveForm::begin(); ?>
            <?php
            echo GridView::widget([
                'dataProvider'=> $dataProvider,
                'columns' => $gridColumns,
                'responsive'=>true,
                'hover'=>true
            ]);
            //$this->includeTemplate(get_template_path($template_name, 'reporting'));

            ?>
            xxx
        </div>
        <div class="panel-footer">
            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>

<?php
//if (!isset($genid)) $genid = gen_id();
if (!isset($allow_export)) $allow_export = true;

?>



