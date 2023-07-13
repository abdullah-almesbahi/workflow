<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\icons\Icon;

?>
<div class="site-about">
    <?php Yii::$app->trigger('frontend/crud/form/before'); ?>
    <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->trigger('frontend/crud/form/before/grid');

    //footer Class
    ob_start();
    Yii::$app->trigger('frontend/crud/form/submit/before');
    if($model->isNewRecord){
        if( Yii::$app->hasEventHandlers('frontend/crud/form/submit/add') ){
            Yii::$app->trigger('frontend/crud/form/submit/add');
        }else{
            echo Html::submitButton( Icon::show('plus').' '.Yii::t('app', 'Create'), ['class' => 'btn btn-success']);
        }
    }else{
        if(Yii::$app->hasEventHandlers('frontend/crud/form/submit/update') ){
            Yii::$app->trigger('frontend/crud/form/submit/update');
        }else{
            echo Html::submitButton( Icon::show('save').' '.Yii::t('app', 'Update'), ['class' => 'btn btn-primary']);
        }
    }
    Yii::$app->trigger('frontend/crud/form/submit/after');
    $footer = ob_get_contents();
    ob_end_clean();

    \backend\widgets\BackendWidget::begin(
        [
            'icon' => 'user',
            'title' => $model->isNewRecord ?Yii::t('app', 'Create'):Yii::t('app', 'Update'),
            //'showLabels' => true,
            'footer' => $footer,
        ]
    );

    //render additional fields
    if(count($fields) > 0){

        foreach($fields as $k => $v){


            unset($field);
            $field = $form->field($model, $v->attributes['name']);
            if($v->attributes['title'] != ''){
                $field->label($v->attributes['title']);
            }
            if($v->attributes['description'] != ''){
                $field->hint($v->attributes['description']);
            }

            Yii::$app->trigger('frontend/crud/form/field/before');
            Yii::$app->trigger('frontend/crud/form/field/before/'.$v->attributes['name']);
            if(Yii::$app->hasEventHandlers('frontend/crud/form/field/'.$v->attributes['name'])){
                Yii::$app->trigger('frontend/crud/form/field/'.$v->attributes['name'] , new Event(['sender' => (object) array_merge((array) ['m'=>$model], (array) ['f'=>$form]) ]));
            }else {
                //check if this field is has option of prevent editing
                $display = explode(', ', $v->attributes['display']);
                if(in_array('no_edit' , $display)){
                    echo  $field->textInput(['maxlength' => 255, 'size' => $v->attributes['size'] , 'disabled' => 'disabled']);
                    continue;
                }
                switch ($v->attributes['field_type']) {
                    case 'text':
                        $field->textInput(['maxlength' => 255, 'size' => $v->attributes['size']]);
                        break;
                    case 'date':
                        break;
                    case 'image':
                        break;
                    case 'mutli_image':
                        break;
                    case 'textarea':
                        $field->textarea(['cols' => $v->attributes['cols'], 'rows' => $v->attributes['rows']]);
                        break;
                    case 'editor':
                        break;
                    case 'multi_select':
                        break;
                    case 'select':
                        if (!empty($v->attributes['custom_func'])) {

                        } elseif (!empty($v->attributes['sql_query'])) {

                        } else {
                            preg_match_all('/^\s*(.*?)\s*\|\s*(.+?)\s*(|\|(.+?))\s*$/m', $v->attributes['options'], $regs);
                            $default = $values = array();
                            foreach ($regs[1] as $i => $k) {
                                $values[$k] = $regs[2][$i];
                                if ($regs[4][$i] == 1)
                                    $default[] = $k;
                            }
                            if ($model->isNewRecord && !empty($default[0])) {
                                $name = $v->attributes['name'];
                                $model->$name = $default[0];
                            }

                            $field->dropDownList($values);
                        }
                        break;
                    case 'radio':
                        break;
                    case 'checkbox':
                        break;
                    case 'custom':
                        break;
                }
                echo $field;
            }


            Yii::$app->trigger('frontend/crud/form/field/after');
            Yii::$app->trigger('frontend/crud/form/field/after/'.$v->attributes['name']);
        }

    }
    ?>
    <?php \backend\widgets\BackendWidget::end(); ?>
    <?php Yii::$app->trigger('frontend/crud/form/after/grid'); ?>
    <?php ActiveForm::end(); ?>
    <?php Yii::$app->trigger('frontend/crud/form/after'); ?>

</div>