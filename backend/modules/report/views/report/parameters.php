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
            <?php

            $form = ActiveForm::begin();
            echo TabularForm::widget([
                'dataProvider'=>$dataProvider,
                'form'=>$form,
                'actionColumn' => false,
                'checkboxColumn' => false,
                'attributes'=> [

                    'field_name'=>[
                        'type'=>TabularForm::INPUT_STATIC,'
                        columnOptions'=>['width'=>'200px']
                    ],
                    'condition'=>[
                        'type'=>TabularForm::INPUT_STATIC,
                        'value'=>function ($model, $key, $index, $column) {
                            return ($model->condition != '%' ? $model->condition : Yii::t('admin' ,'ends with') );
                        },
                        'columnOptions'=>['width'=>'50px']

                    ],
                    'value'=>[
                        'type'=>TabularForm::INPUT_WIDGET,
                        'widgetClass'=>\kartik\widgets\DatePicker::classname(),
                        'pluginOptions' => [
                            'autoclose'=>true,
                        ],


                    ],
                    // primary key column
                    'id'=>[ // primary key attribute
                        'type'=>TabularForm::INPUT_HIDDEN,
                        'columnOptions'=>['hidden'=>true]

                    ],


                ]
            ]);

            ?>
        </div>
        <div class="panel-footer">
            <?= \yii\helpers\Html::submitButton('Generate Report', ['class'=>'btn btn-primary']); ?>
            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>
if ($condition->getFieldName() == 'is_user') {
$options = array(option_tag(lang('yes'), 1), option_tag(lang('no'), 0));
echo select_box("params[".$condition->getId()."]", $options);
} else if ($col_type == DATA_TYPE_DATE || $col_type == DATA_TYPE_DATETIME) {
echo pick_date_widget2("params[".$condition->getId()."]");
} else {
?>


