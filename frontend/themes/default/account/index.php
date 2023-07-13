<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
$this->title = 'My Account';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('tabs', [

    ]) ?>

    <?php Yii::$app->trigger('backend/crud/form/before'); ?>
    <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->trigger('backend/crud/form/before/grid');

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

            Yii::$app->trigger('backend/crud/form/field/before');
            Yii::$app->trigger('backend/crud/form/field/before/'.$v->attributes['name']);
            if(Yii::$app->hasEventHandlers('backend/crud/form/field/'.$v->attributes['name'])){
                Yii::$app->trigger('backend/crud/form/field/'.$v->attributes['name'] , new Event(['sender' => (object) array_merge((array) ['m'=>$model], (array) ['f'=>$form]) ]));
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


            Yii::$app->trigger('backend/crud/form/field/after');
            Yii::$app->trigger('backend/crud/form/field/after/'.$v->attributes['name']);
        }

    }
    ?>
    <?php Yii::$app->trigger('backend/crud/form/after/grid'); ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Yii::$app->trigger('backend/crud/form/after'); ?>

</div>