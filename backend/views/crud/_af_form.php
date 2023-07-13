<?php


use backend\widgets\BackendWidget;
use kartik\helpers\Html;
use kartik\icons\Icon;
use kartik\widgets\ActiveForm;
use yii\web\View;

$inline_radio_options = ['options' => ['class' => 'radio radio-primary radio-inline'], 'template' => '{input}{label}{hint}{error}'];
$radio_options = ['options' => ['class' => 'radio radio-primary'], 'template' => '{input}{label}{hint}{error}'];

/* @var $this yii\web\View */
/* @var $model backend\models\Evooy */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
//    BackendWidget::begin(
//        [
//            //'icon' => 'user',
//            'title' => $model->isNewRecord ? Yii::t('admin', '') : Yii::t('admin', ''),
//            //'showLabels' => true,
//            'footer' => Html::submitButton(
//                    Icon::show('save') . ($model->isNewRecord ? Yii::t('admin', 'Create') : Yii::t('admin', 'Update')), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'button', 'value' => 'update']
//                ) . Html::submitButton(
//                    ($model->isNewRecord ? Yii::t('admin', 'Create & reload') : Yii::t('admin', 'Update & reload')), ['class' => $model->isNewRecord ? 'btn btn-success m-l-10 m-r-10' : 'btn btn-primary m-l-10 m-r-10', 'name' => 'button', 'value' => 'reload']
//                ),
//        ]
//    );
?>
<?php
// Start MineOptions Tabs
// Tabs:
// 1. Start Workflow Tabs
// 2. Start Edit Field Tabs
// 3. Start Conditional Logic Tabs
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
<?php $form = ActiveForm::begin(['id' => 'form-fields']); ?>
<div class="col-md-12">
    <ul class="nav nav-tabs" id="tab-01">
        <?php if (($model->name == 'status' && $model->enable_workflow == 1) || ($model->name == 'status_ex' && $model->enable_workflow == 1)): ?>
            <li class="active"><a href="#tabWorkflow" role="tab" data-toggle="tab">Workflow</a></li>
            <li><a href="#tabEditField" role="tab" data-toggle="tab">Edit Field</a></li>
        <?php else : ?>
            <li class="active"><a href="#tabEditField" role="tab" data-toggle="tab">Edit Field</a></li>
        <?php endif; ?>
        <li><a href="#tabConditional" role="tab" data-toggle="tab">Conditional Logic</a></li>
    </ul>

    <div class="tools"><a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal"
                                                                       class="config"></a> <a href="javascript:;"
                                                                                              class="reload"></a> <a
            href="javascript:;" class="remove"></a></div>
    <div class="tab-content">
        <?php // 1. Start Workflow Tabs
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
        <?php if (($model->name == 'status' && $model->enable_workflow == 1) || ($model->name == 'status_ex' && $model->enable_workflow == 1)): ?>
            <div class="tab-pane active" id="tabWorkflow">

                <div class="row column-seperation">
                    <div class="col-md-12">
                        <div class="row b-b b-dashed b-grey m-b-10">
                            <div class="col-md-3">
                                <div class="col-xs-12 col-md-4"><label
                                        class="semi-bold m-r-5 m-t-10"><?= Yii::t('admin', 'Initial Status'); ?></label>
                                </div>
                                <div
                                    class="col-xs-12 col-md-8"><?= $form->field($model, "wf_initial", ['template' => '{input}{hint}{error}'])->textInput(['class' => 'form-control'])->label(); ?></div>
                            </div>
                            <div class="col-md-9">
                                <div
                                    class="alert alert-info text-white"><?= \Yii::t('admin', 'Note : You need to save the page so you can select workflow status if it was empty , Do the same thing if you add new status') ?></div>
                            </div>
                        </div>


                        <div class="tabbable tabs-left" id="table_workflow">
                            <ul class="nav nav-tabs nav-tabs-secondary" id="tab-2">
                                <?php
                                if ($model->isNewRecord || empty($model->options)) {
                                    $workflow_field = [];
                                } else {
                                    preg_match_all('/^\s*(.*?)\s*\|\s*(.+?)\s*(|\|(.+?))\s*$/m', $model->options, $regs);
                                    $workflow_field = array();
                                    foreach ($regs[1] as $i => $k) {
                                        $workflow_field[$k] = $regs[2][$i];
                                    }
                                }
                                $__i = 0;
                                foreach (\Yii::$app->getAuthManager()->getRoles() as $k => $v):
                                    ?>
                                    <li <?php if ($__i == 0) {
                                        echo 'class="active"';
                                    } ?>>
                                        <a role="tab" data-toggle="tab" href="#<?= \yii\helpers\Inflector::camelize((!empty($v->description)) ? $v->description : $v->name); ?>"><?= (!empty($v->description)) ? $v->description : $v->name; ?></a>
                                    </li>
                                    <?php $__i++; endforeach; ?>

                            </ul>
                            <div class="tab-content" style="background: #f8f8f8">
                                <?php
                                if ($model->isNewRecord || empty($model->options)) {
                                    $workflow_field = [];
                                } else {
                                    preg_match_all('/^\s*(.*?)\s*\|\s*(.+?)\s*(|\|(.+?))\s*$/m', $model->options, $regs);
                                    $workflow_field = array();
                                    foreach ($regs[1] as $i => $k) {
                                        $workflow_field[$k] = $regs[2][$i];
                                    }
                                }
                                $i_mine = 0;
                                foreach (\Yii::$app->getAuthManager()->getRoles() as $k => $v):
                                    ?>
                                    <div class="tab-pane <?php if ($i_mine == 0) {
                                        echo 'active';
                                    } ?>"
                                         id="<?= \yii\helpers\Inflector::camelize((!empty($v->description)) ? $v->description : $v->name); ?>">
                                        <h1 class="b-b b-orange p-b-10 m-t-0 semi-bold"><?= (!empty($v->description)) ? $v->description : $v->name; ?></h1>
                                        <div class="row column-seperation">
                                            <?php //////// Start column left Status tables
                                            /////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <div class="col-md-6">

                                                <div class="row">
                                                    <?php if ($model->name == 'status'): ?>
                                                        <div class="col-md-12 m-b-20">
                                                            <div class="b-b b-grey p-b-20">
                                                                <h3 class="semi-bold"><?= Yii::t('admin', 'Tables Columns') ?></h3>

                                                                <p><?= Yii::t('admin', 'Select the columns you want to display for this group') ?></p>
                                                                <?php
                                                                echo \kartik\select2\Select2::widget([
                                                                    'model' => $model,
                                                                    'attribute' => "wf_field_index[{$k}]",
                                                                    'data' => \yii\helpers\ArrayHelper::map($all_fields_index, 'id', 'title'),
                                                                    'options' => ['placeholder' => Yii::t('admin', 'Select fields to display in table.'), 'multiple' => true],
                                                                    'pluginOptions' => [
                                                                        'allowClear' => true,
                                                                    ],
                                                                ]);
                                                                ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ($model->name == 'status'): ?>
                                                        <div class="col-md-12">
                                                            <div class="b-b b-grey p-b-20">
                                                                <h3 class="semi-bold"><?= Yii::t('admin', 'Fields in form') ?></h3>

                                                                <p><?= Yii::t('admin', 'Select the columns you want to display for this group') ?></p>

                                                                <div class="row">
                                                                    <div class="wf_field_update-wrapper form-inline">

                                                                        <?php
                                                                        echo '<div class="col-md-12 m-b-20">';
                                                                        echo \kartik\select2\Select2::widget([
                                                                            'model' => $model,
                                                                            'attribute' => "wf_field_update[{$k}]",
                                                                            'data' => \yii\helpers\ArrayHelper::map($all_fields_update, 'id', 'title'),
                                                                            'options' => ['placeholder' => Yii::t('admin', 'Select fields to display in form.'), 'multiple' => true],
                                                                            'pluginOptions' => [
                                                                                'allowClear' => true,
                                                                            ],
                                                                        ]);
                                                                        echo '</div>';
                                                                        //print_r($model->wf_enable_md);die();
                                                                        if (isset($model->wf_enable_md[$k]) && $model->wf_enable_md[$k] == 1) {
                                                                            echo "";
                                                                            if (isset($model->wf_definition_from[$k]) && is_array($model->wf_definition_to)) {
                                                                                //print_r($model->wf_definition_from[$k]);print_r($workflow_field);die();
                                                                                foreach ($model->wf_definition_from[$k] as $kk => $vv):
                                                                                    if (!isset($workflow_field[$vv])) {
                                                                                        continue;
                                                                                    }
                                                                                    //foreach($vv as $kkk => $vvv):
                                                                                    echo '<div class="form-inline col-md-6 m-tb-10">'
                                                                                        . '<div class="b-b b-grey p-tb-10">'
                                                                                        . '<div class="pull-right wfs_checkbox">';
                                                                                    echo $form->field($model, "wf_ignore_md[{$k}][$kk]", ['options' => ['class' => 'checkbox check-primary m-l-20'], 'template' => '{input}{label}'])->checkbox(
                                                                                        [
                                                                                            'value' => 1,
                                                                                            'id' => "wf_ignore_md_{$k}_" . $vv,
                                                                                            'uncheck' => null,
                                                                                            'labelOptions' => ['for' => "wf_ignore_md_{$k}" . $vv]
                                                                                        ], false)->label(\Yii::t('admin', 'Ignore?'));
                                                                                    //print_r($workflow_field);die($vv);
                                                                                    echo '</div>';

                                                                                    echo $workflow_field[$vv];
                                                                                    echo \kartik\select2\Select2::widget([
                                                                                        'model' => $model,
                                                                                        'attribute' => "wf_fields_md[{$k}][{$kk}]",
                                                                                        'data' => \yii\helpers\ArrayHelper::map($all_fields_update, 'id', 'title'),
                                                                                        'options' => ['placeholder' => Yii::t('admin', 'Select fields to display in form.'), 'multiple' => true],
                                                                                        'pluginOptions' => [
                                                                                            'allowClear' => true,
                                                                                        ],
                                                                                    ]);
                                                                                    echo '</div>'
                                                                                        . '</div>';

                                                                                endforeach;
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    <?php endif; ?>


                                                </div>
                                            </div>

                                            <?php //////// Start column Right Status tables
                                            /////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <?php if ($model->name == 'status'): ?>
                                                        <div class="col-md-12">
                                                            <div class="b-b b-grey p-b-20">
                                                                <h3 class="semi-bold"><?= Yii::t('admin', 'Fields For viewing Only') ?></h3>

                                                                <p><?= Yii::t('admin', 'Select the columns you want to display for this group') ?></p>

                                                                <?php
                                                                echo \kartik\select2\Select2::widget([
                                                                    'model' => $model,
                                                                    'attribute' => "wf_field_view[{$k}]",
                                                                    'data' => \yii\helpers\ArrayHelper::map($all_fields_update, 'id', 'title'),
                                                                    'options' => ['placeholder' => Yii::t('admin', 'Select fields for viewing only in form.'), 'multiple' => true],
                                                                    'pluginOptions' => [
                                                                        'allowClear' => true,
                                                                    ],
                                                                ]);
                                                                ?>

                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="col-md-12 m-t-10">
                                                        <h3 class="semi-bold"><?= Yii::t('admin', 'Workflow status') ?></h3>

                                                        <p><?= Yii::t('admin', 'Select the columns you want to display for this group') ?></p>

                                                        <p></p>

                                                        <div class="workflow-wrapper form-inline wfs_checkbox">
                                                            <div class="row">
                                                                <?php if (!$model->isNewRecord || !empty($model->options)) : ?>
                                                                    <?php
                                                                    echo '<div class="col-md-12">';
                                                                    echo $form->field($model, "wf_definition_initial[{$k}]", ['options' => ['class' => 'checkbox check-primary m-l-20'], 'template' => '{input}{label}{hint}{error}'])->checkbox(
                                                                        [
                                                                            'value' => 1,
                                                                            'id' => "wf_definition_initial_{$k}",
                                                                            'uncheck' => null,
                                                                            'labelOptions' => ['for' => "wf_definition_initial_{$k}"]
                                                                        ], false)->label(\Yii::t('admin', 'Initial Status'));
                                                                    echo '</div>';
                                                                    ?>
                                                                    <?php

                                                                    if (!isset($model->wf_definition_from[$k]) && is_array($model->wf_definition_from)) {
                                                                        $model->wf_definition_from = array_merge($model->wf_definition_from, [$k => ['0' => '']]);
                                                                    } elseif (!isset($model->wf_definition_from[$k]) && !is_array($model->wf_definition_from)) {
                                                                        $model->wf_definition_from = [$k => ['0' => '']];
                                                                    }

                                                                    $model->wf_definition_to = !is_array($model->wf_definition_to) ? '' : $model->wf_definition_to;
                                                                    foreach ($model->wf_definition_from[$k] as $kk => $vv):
                                                                        echo '<div class="form-inline"><div class=" col-md-6 b-b b-dashed b-grey m-b-10 wfs_fullWidth">';
                                                                        echo '<a class="delete_field_choice remove-workflow text-danger pull-right" title="remove this rule"><i class="fa fa-minus-square fa-lg"></i> </a>';
                                                                        echo $form->field($model, "wf_definition_from[{$k}][$kk]")->dropDownList(array_merge(['' => \Yii::t('admin', 'Please select')], $workflow_field), ['class' => 'wf_transtition form-control m-r-5'])->label(Yii::t('admin', 'From'));
                                                                        echo '</div>';
                                                                        echo '<div class=" col-md-6 b-b b-dashed b-grey m-b-10 wfs_fullWidth">';
                                                                        echo $form->field($model, "wf_definition_to[{$k}][$kk]")->textInput(['class' => 'wf_transtition'])->label(\Yii::t('admin', 'Transitions'));
                                                                        echo '</div></div>';
                                                                    endforeach;
                                                                endif; ?>
                                                                <div class="col-md-12">
                                                                    <button
                                                                        class="btn btn-info btn-block add-workflow"
                                                                        type="button"><i
                                                                            class="fa fa-plus-square fa-lg"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div><?php //////// END column Right Status tables
                                                    /////////////////////////////////////////////////////////////////////////////////////
                                                    ?>
                                                    <?php if ($model->name == 'status_ex'): ?>
                                                        <div class="col-md-12 m-t-10">
                                                        <h3 class="semi-bold"><?= Yii::t('admin', 'Workflow Extra status') ?></h3>

                                                        <p><?= Yii::t('admin', 'Select the columns you want to display for this group') ?></p>

                                                        <div class="workflow-wrapper form-inline">
                                                            <div class="row">
                                                                <?php if (!$model->isNewRecord || !empty($model->options)) : ?>

                                                                    <?php

                                                                    if (!isset($model->wf_definition_from[$k])) {
                                                                        $model->wf_definition_from = array_merge($model->wf_definition_from, [$k => ['0' => '']]);
                                                                    }

                                                                    $model->wf_definition_to = !is_array($model->wf_definition_to) ? '' : $model->wf_definition_to;

                                                                    $status = (new \backend\models\AF($model->table))->getStatusField();
                                                                    $status['wf_validate_from'] = is_array($status['wf_validate_from']) ? $status['wf_validate_from'] : unserialize($status['wf_validate_from']);
                                                                    $status['wf_validate_to'] = is_array($status['wf_validate_to']) ? $status['wf_validate_to'] : unserialize($status['wf_validate_to']);
                                                                    if (!isset($status['wf_validate_from'][$k])) {
                                                                        $status['wf_validate_from'] = [$k => ['0' => '']];
                                                                    }
                                                                    preg_match_all('/^\s*(.*?)\s*\|\s*(.+?)\s*(|\|(.+?))\s*$/m', $status['options'], $_regs);
                                                                    $p_workflow_field = array();
                                                                    foreach ($_regs[1] as $__i => $__k) {
                                                                        $p_workflow_field[$__k] = $_regs[2][$__i];
                                                                    }
                                                                    //print_r($model->wf_definition_from);die($k);
                                                                    foreach ($model->wf_definition_from[$k] as $kk => $vv):
                                                                        echo '<div class="form-inline col-md-6 b-b b-dashed b-grey m-b-10 wfs_fullWidth">';
                                                                        echo $form->field($model, "wf_definition_from[{$k}][$kk]")->dropDownList(array_merge(['' => \Yii::t('admin', 'Please select')], $workflow_field), ['style' => 'width:150px;', 'class' => 'wf_definition'])->label(Yii::t('admin', 'From'));
                                                                        echo $form->field($model, "wf_definition_to[{$k}][$kk]")->textInput(['style' => 'width:150px;', 'class' => 'wf_transtition'])->label(\Yii::t('admin', 'Transitions'));
                                                                        ?>
                                                                        <div class="well">
                                                                            <strong>Related validation with Primary
                                                                                status:</strong><br/><br/>
                                                                            <?php

                                                                            if (isset($model->wf_validate_from[$k])) {
                                                                                foreach ($model->wf_validate_from[$k] as $kk2 => $vv2):
                                                                                    //get a key of array

                                                                                    if ($vv == $kk2) {
                                                                                        foreach ($vv2 as $related_key => $related_value):
                                                                                            ?>
                                                                                            <div
                                                                                                class="form-inline col-md-6 b-b b-dashed b-grey m-b-10 wfs_fullWidth">
                                                                                            <a class="delete_field_choice remove-workflow-related text-danger pull-right m-t-60"
                                                                                               title="remove this rule"><i
                                                                                                    class="fa fa-minus-square fa-lg"></i></a>
                                                                                            <?php
                                                                                            echo $form->field($model, "wf_validate_from[{$k}][$vv][{$related_key}]")->dropDownList(array_merge(['' => \Yii::t('admin', 'Please select')], $p_workflow_field), ['style' => 'width:150px;', 'class' => 'wf_definition'])->label(Yii::t('admin', 'From'));
                                                                                            echo $form->field($model, "wf_validate_to[{$k}][$vv][{$related_key}]")->textInput(['style' => 'width:150px;', 'class' => 'wf_transtition'])->label(\Yii::t('admin', 'Transitions'));
                                                                                            ?>

                                                                                            </div><?php
                                                                                        endforeach;
                                                                                    }
                                                                                endforeach;
                                                                            }
                                                                            ?>
                                                                            <button
                                                                                class="btn btn-info btn-xs btn-mini add-workflow-related"
                                                                                type="button"><i
                                                                                    class="fa fa-plus-square fa-lg"></i>
                                                                            </button>
                                                                        </div>
                                                                        <?php
                                                                        echo '</div>';
                                                                    endforeach;
                                                                endif; ?>

                                                            </div>
                                                        </div>

                                                        </div><?php endif; //////// END column Right Status tables
                                                    /////////////////////////////////////////////////////////////////////////////////////
                                                    ?>

                                                </div>


                                            </div>
                                            <?php /////////////// Start Full width column
                                            ////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <div class="clearfix"></div>
                                            <div class="col-md-12 b-t b-grey p-b-20">


                                                <?php if ($model->name == 'status'): ?>
                                                    <div class="col-md-12">
                                                        <h3 class="semi-bold"><?= Yii::t('admin', 'Options') ?></h3>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="b-r b-grey p-r-20 m-t-40">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-inline">
                                                                        <label> <span class="semi-bold"><?= (!empty($v->description)) ? $v->description : $v->name; ?></span> Clone To:</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <?php
                                                                    echo Html::dropDownList("cloneWorkflow[{$k}]", null, \yii\helpers\ArrayHelper::map(
                                                                        \Yii::$app->getAuthManager()->getRoles(),
                                                                        'name',
                                                                        function ($item) {
                                                                            return $item->name . (strlen($item->description) > 0
                                                                                ? ' [' . $item->description . ']'
                                                                                : '');
                                                                        }
                                                                    ), ['size' => 10, 'style' => 'width:100%;', 'multiple' => true])
                                                                    ?>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>


                                                    <div class="col-md-6">
                                                        <div class="m-t-40">

                                                            <div>
                                                                <?php
                                                                echo $form->field($model, "wf_view_all[{$k}]", ['options' => ['class' => 'checkbox check-primary m-l-20'], 'template' => '{input}{label}{hint}{error}'])->checkbox(
                                                                    [
                                                                        'value' => 1,
                                                                        'id' => "wf_view_all_{$k}",
                                                                        'uncheck' => null,
                                                                        'labelOptions' => ['for' => "wf_view_all_{$k}"]
                                                                    ], false)->label(\Yii::t('admin', 'Ability To VIEW ONLY All Results in any status.'));
                                                                echo $form->field($model, "wf_view_by_owner[{$k}]", ['options' => ['class' => 'checkbox check-primary m-l-20'], 'template' => '{input}{label}{hint}{error}'])->checkbox(
                                                                    [
                                                                        'value' => 1,
                                                                        'id' => "wf_view_by_owner_{$k}",
                                                                        'uncheck' => null,
                                                                        'labelOptions' => ['for' => "wf_view_by_owner_{$k}"]
                                                                    ], false)->label(\Yii::t('admin', 'Ability To VIEW ONLY All Results Added by this user'));
                                                                echo $form->field($model, "wf_view_audit_trail[{$k}]", ['options' => ['class' => 'checkbox check-primary m-l-20'], 'template' => '{input}{label}{hint}{error}'])->checkbox(
                                                                    [
                                                                        'value' => 1,
                                                                        'id' => "wf_view_audit_trail_{$k}",
                                                                        'uncheck' => null,
                                                                        'labelOptions' => ['for' => "wf_view_audit_trail_{$k}"]
                                                                    ], false)->label(\Yii::t('admin', 'Ability To View Audit trail'));
                                                                echo $form->field($model, "wf_assign[{$k}]", ['options' => ['class' => 'checkbox check-primary m-l-20'], 'template' => '{input}{label}{hint}{error}'])->checkbox(
                                                                    [
                                                                        'value' => 1,
                                                                        'id' => "wf_assign_{$k}",
                                                                        'uncheck' => null,
                                                                        'labelOptions' => ['for' => "wf_assign_{$k}"]
                                                                    ], false)->label(\Yii::t('admin', 'Ability To Assign record to an agent and hide from other users in same role group'));
                                                                echo $form->field($model, "wf_enable_md[{$k}]", ['options' => ['class' => 'checkbox check-primary m-l-20'], 'template' => '{input}{label}{hint}{error}'])->checkbox(
                                                                    [
                                                                        'value' => 1,
                                                                        'id' => "wf_enable_md_{$k}",
                                                                        'uncheck' => null,
                                                                        'labelOptions' => ['for' => "wf_enable_md_{$k}"]
                                                                    ], false)->label(\Yii::t('admin', 'Ability To display fields on specific transition.'));
                                                                ?>
                                                            </div>


                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i_mine++; endforeach; ?>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        <?php endif; ?>
        <?php // 2. Start Edit Field Tabs
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
        <div
            class="tab-pane <?php if (($model->name == 'status' && $model->enable_workflow == 1) || ($model->name == 'status_ex' && $model->enable_workflow == 1)): else: echo 'active'; endif; ?>"
            id="tabEditField">
            <div class="row">
                <!-- Start Left Col -------------------------------->
                <div class="col-md-6 b-r b-dashed b-grey">
                    <div class="row form-row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => 255])->label('Field Name (ENGLISH) - internal') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'title')->textInput(['maxlength' => 255])->label('Field Name') ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'description')->textarea(['maxlength' => 255])->label('Description about the field'); ?>
                        </div>
                        <div class="col-md-12 b-t b-dashed b-grey p-t-10">
                            <div class="field-af-sql-type">
                                <label><?= Yii::t('admin', 'SQL Type') ?></label>
                                <?= $form->field($model, 'sql_type', $inline_radio_options)->radio(
                                    [
                                        'value' => 'varchar(255)',
                                        'id' => 'varchar',
                                        'uncheck' => null,
                                        'labelOptions' => ['for' => 'varchar']
                                    ], false)->label(Yii::t('admin', 'String ( VARCHAR(255) )')) ?>
                                <?= $form->field($model, 'sql_type', $inline_radio_options)->radio(
                                    [
                                        'value' => 'blob',
                                        'id' => 'blob',
                                        'uncheck' => null,
                                        'labelOptions' => ['for' => 'blob']
                                    ], false)->label(Yii::t('admin', 'Blob (unlimited length string/data)')) ?>
                                <?= $form->field($model, 'sql_type', $inline_radio_options)->radio(
                                    [
                                        'value' => 'INT',
                                        'id' => 'INT',
                                        'uncheck' => null,
                                        'labelOptions' => ['for' => 'init']
                                    ], false)->label(Yii::t('admin', 'Integer field (only numbers)')) ?>
                                <?= $form->field($model, 'sql_type', $inline_radio_options)->radio(
                                    [
                                        'value' => 'decimal(12,2)',
                                        'class' => 'm-l-5',
                                        'id' => 'DECIMAL(12,2)',
                                        'uncheck' => null,
                                        'labelOptions' => ['for' => 'DECIMAL(12,2)']
                                    ], false)->label(Yii::t('admin', 'Numeric field (DECIMAL(12,2))')) ?>
                            </div>
                        </div>
                        <div class="col-md-12 b-t b-dashed b-grey p-t-10 form_radio">
                            <div class="form-group field-af-field-type radio_min">
                                <label><?= Yii::t('admin', 'Field Type') ?></label>
                                <?php
                                $field_types = array(
                                    'text' => Yii::t('admin', 'String'),
                                    'select' => Yii::t('admin', 'Select'),
                                    'multi_select' => Yii::t('admin', 'Multi Select'),
                                    'textarea' => Yii::t('admin', 'Text Area'),
                                    'editor' => Yii::t('admin', 'Editor'),
                                    'radio' => Yii::t('admin', 'Radio'),
                                    'checkbox' => Yii::t('admin', 'Checkbox'),
                                    'date' => Yii::t('admin', 'Date'),
                                    'image' => Yii::t('admin', 'Image'),
                                    'multi_image' => Yii::t('admin', 'Multi Images'),
                                    'custom' => Yii::t('admin', 'Custom Field'),
                                    'array' => Yii::t('admin', 'Array'),
                                );
                                foreach ($field_types as $k => $v) {
                                    echo $form->field($model, 'field_type', $inline_radio_options)->radio(
                                        [
                                            'value' => $k,
                                            'id' => $k,
                                            'uncheck' => null,
                                            'onclick' => 'switch_layers(this.value)',
                                            'labelOptions' => ['for' => $k]
                                        ], false)->label($v);
                                }

                                ?>
                            </div>
                        </div>

                        <div class="col-md-6 b-t b-dashed b-grey p-t-10">
                            <div class="form-group field-af-admin-display">
                                <label><?= Yii::t('admin', 'Allow to Display Field In Backend for/in:-') ?></label>
                                <?php
                                $admin_display = array(
                                    'index' => Yii::t('admin', 'Display in managing table page'),
                                    'update' => Yii::t('admin', 'Display in edit page'),
                                );
                                echo \kartik\select2\Select2::widget([
                                    'model' => $model,
                                    'attribute' => 'admin_display',
                                    'data' => $admin_display,
                                    'options' => ['placeholder' => Yii::t('admin', 'Select where to display this field.'), 'multiple' => true],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>

                        <div class="col-md-6 b-t b-dashed b-grey p-t-10">
                            <div class="form-group field-af-display">
                                <label><?= Yii::t('admin', 'Display Field In frontend') ?></label>
                                <?php
                                $validate_funcs = array(
                                    'no_edit' => Yii::t('admin', 'Prevent editing'),
                                    'signup' => Yii::t('admin', 'Display in signup Page'),
                                    'profile' => Yii::t('admin', 'Display in Profile Page'),
                                    'all_pages' => Yii::t('admin', 'Display in All Other Pages'),
                                );
                                echo \kartik\select2\Select2::widget([
                                    'model' => $model,
                                    'attribute' => 'display',
                                    'data' => $validate_funcs,
                                    'options' => ['placeholder' => Yii::t('admin', 'Select display options.'), 'multiple' => true],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ]);
                                //    foreach($validate_funcs as $k => $v){
                                //        echo $form->field($model, 'display', $inline_radio_options)->radio(
                                //            [
                                //                'value' => $k,
                                //                'id' => $k,
                                //                'uncheck' => null,
                                //                'labelOptions' => ['for' => $k]
                                //            ], false)->label($v);
                                //    }
                                ?>
                            </div>
                        </div>

                        <div class="col-md-12 b-t b-dashed b-grey p-t-10">
                            <div class="form-group field-af-validate-func">
                                <label><?= Yii::t('admin', 'Validate function') ?></label>
                                <?php
                                $validate_funcs = array(
                                    'none' => Yii::t('admin', 'No validation'),
                                    'require' => Yii::t('admin', 'Required value'),
                                    'integer' => Yii::t('admin', 'Integer value'),
                                    'number' => Yii::t('admin', 'Numeric value'),
                                    'email' => Yii::t('admin', 'Email value'),
                                );
                                foreach ($validate_funcs as $k => $v) {
                                    echo $form->field($model, 'validate_func', $inline_radio_options)->radio(
                                        [
                                            'value' => $k,
                                            'id' => $k,
                                            'uncheck' => null,
                                            'labelOptions' => ['for' => $k]
                                        ], false)->label($v);
                                }
                                ?>
                            </div>
                            <div class="col-md-12 b-t b-dashed b-grey p-t-10">
                                <?=
                                $form->field($model, 'enable_workflow', ['options' => ['class' => 'checkbox check-primary'], 'template' => '{input}{label}{hint}{error}'])->checkbox(
                                    [
                                        'value' => '1',
                                        'id' => 'enable_workflow',
                                        'uncheck' => null,
                                        'labelOptions' => ['for' => 'enable_workflow']
                                    ], false)->label(Yii::t('admin', 'Enable Workflow Engine'));
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Start Right Col -------------------------------->
                <div class="col-md-6 b-r b-dashed b-grey">
                    <h3>Field Type Options</h3>

                    <div class="row form-row">

                        <div class="col-md-12">
                            <?= $form->field($model, 'options', ['options' => ['style' => 'display:none;', 'id' => 'values']])->textarea(['maxlength' => 1000,'rows' => 15,'style'=>'direction:ltr;'])->hint(Yii::t('admin', '
<div class="bg-warning p-a-10 clearfix">
<div class="row">
<div class="col-md-12">
This list displays pipe-separated list of field keys (internal values), values (human-readable) and default value indicators (1-default value, 0-not default value) For example, the following lines will<br>
create country list, where USA is selected by default: USA|United States|1 UK|United Kingdom|0 CA|Canada|0
</div>
</div>
</div>


                  ')); ?>
                            <div id="show_time" style='display: none;' dir="ltr">
                                <?= $form->field($model, 'show_time')->textInput(['maxlength' => 255]) ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <?= $form->field($model, 'sql_query', ['options' => ['style' => 'display:none;', 'id' => 'sql_query']])->textarea(['rows' => 5]) ?>
                            <?= $form->field($model, 'size', ['options' => ['style' => 'display:none;', 'id' => 'size']])->textInput(['maxlength' => 2550]) ?>
                            <div id=textarea_size style='display: none;'>
                                <?= $form->field($model, 'cols')->textInput(['maxlength' => 255]) ?>
                                <?= $form->field($model, 'rows')->textInput(['maxlength' => 255]) ?>
                            </div>
                            <div id="image_size" style='display: none;'>
                                <?= $form->field($model, 'width')->textInput(['maxlength' => 255]) ?>
                                <?= $form->field($model, 'width2')->textInput(['maxlength' => 255]) ?>
                            </div>
                            <?= $form->field($model, 'default', ['options' => ['style' => 'display:none;', 'id' => 'text_default']])->textInput(['maxlength' => 255]) ?>
                            <div id=image style='display: none;'>
                                image
                                <?php //$form->field($model, 'image')->textInput(['maxlength' => 255]) ?>
                            </div>
                            <div id="custom" style='display: none;'>
                                <?php // $form->field($model, 'custom')->textInput(['maxlength' => 255]) ?>
                            </div>


                        </div>


                    </div>


                </div>
                <!-- End Right Col -------------------------------->

            </div>
        </div>
        <?php // 3. Start Conditional Logic Tabs
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
        <div class="tab-pane" id="tabConditional">
            <div class="row">
                <div class="col-md-12">

                    <div class="field-af-validate-func m-b-10">
                        <?=
                        $form->field($model, 'enable_condition', ['options' => ['class' => 'checkbox check-primary'], 'template' => '{input}{label}{hint}{error}'])->checkbox(
                            [
                                'value' => '1',
                                'id' => 'enable_conditional',
                                'uncheck' => null,
                                'labelOptions' => ['for' => 'enable_conditional']
                            ], false)->label(Yii::t('admin', 'Enable Conditional Logic'));
                        ?>
                        <div id="enable_conditional_content" style="display: none;">

                            <table class="table table-bordered" id="table_conditional">
                                <thead>
                                <tr>
                                    <th width="300"><?= Yii::t('admin', 'Condition Status') ?></th>
                                    <th><?= Yii::t('admin', 'Conditional logical') ?></th>
                                    <th width="400"><?= Yii::t('admin', 'Actions') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                //Loading default values if not set to avoid warning message
                                $model->c_action = !is_array($model->c_action) ? ['0' => \Yii::t('admin', '-- action --')] : $model->c_action;
                                $model->c_if = !is_array($model->c_if) ? ['0' => \Yii::t('admin', 'all')] : $model->c_if;
                                $model->c_table = !is_array($model->c_table) ? ['0' => \Yii::t('admin', 'all')] : $model->c_table;
                                $model->c_field = !is_array($model->c_field) ? ['0' => \Yii::t('admin', 'all')] : $model->c_field;
                                $model->c_option = !is_array($model->c_option) ? ['0' => \Yii::t('admin', 'all')] : $model->c_option;
                                $model->c_template = !is_array($model->c_template) ? ['0' => \Yii::t('admin', 'all')] : $model->c_template;
                                $model->c_user = !is_array($model->c_user) ? ['0' => \Yii::t('admin', 'all')] : $model->c_user;

                                $c_action_options = [
                                    '0' => \Yii::t('admin', '-- Action --'),
                                    'update_field' => \Yii::t('admin', 'Update Field'),
                                    'prevent' => \Yii::t('admin', 'Prevent'),
                                    'email' => \Yii::t('admin', 'Send Email'),
                                    'sms' => \Yii::t('admin', 'Send SMS'),
                                    'hide' => \Yii::t('admin', 'Hide'),
                                    'show' => \Yii::t('admin', 'Show'),
                                ];
                                $c_if = [
                                    'logicalAnd' => \Yii::t('admin', 'All'),
                                    'logicalOr' => \Yii::t('admin', 'Any'),
                                ];

                                $c_condition = [
                                    'equalTo' => Yii::t('admin', 'Equal To'),
                                    'notEqualTo' => Yii::t('admin', 'Not Equal To'),
                                    'greaterThan' => Yii::t('admin', 'Greater Than'),
                                    'greaterThanOrEqualTo' => Yii::t('admin', 'Greater Than Or Equal To'),
                                    'lessThan' => Yii::t('admin', 'Less Than'),
                                    'lessThanOrEqualTo' => Yii::t('admin', 'Less Than Or Equal To'),
                                    'stringContains' => Yii::t('admin', 'Contain'),
                                    'stringDoesNotContain' => Yii::t('admin', 'Does Not Contain'),
                                    'stringContainsInsensitive' => Yii::t('admin', 'Contain (Insensitive)'),
                                    'stringDoesNotContainInsensitive' => Yii::t('admin', 'Does Not Contain (Insensitive)'),
                                    'startsWith' => Yii::t('admin', 'Start with'),
                                    'startsWithInsensitive' => Yii::t('admin', 'Start with (Insensitive)'),
                                    'endsWith' => Yii::t('admin', 'End with'),
                                    'endsWithInsensitive' => Yii::t('admin', 'End with (Insensitive)'),
                                    'sameAs' => Yii::t('admin', 'Same as'),
                                    'notSameAs' => Yii::t('admin', 'Not same as'),
                                ];

                                foreach ($model->c_action as $k => $v):
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="form-inline">
                                                <?= $form->field($model, "c_action[{$k}]")->dropDownList($c_action_options, ['style' => 'width:150px;', 'class' => 'actions'])->label(Yii::t('admin', 'Do')); ?>
                                                <?= $form->field($model, "c_if[{$k}]")->dropDownList($c_if, ['style' => 'width:80px;', 'class' => 'if'])->label(Yii::t('admin', 'IF')); ?>
                                                <label> <?= Yii::t('admin', 'Following conditions match:-') ?></label>
                                            </div>

                                        </td>
                                        <td>

                                            <?php

                                            $model->c_condition = !is_array( $model->c_condition)?['0'=> ['0' => 'equalto']]:$model->c_condition;
                                            $model->c_value = !is_array( $model->c_value)?[]:$model->c_value;
                                            foreach($model->c_condition[$k] as $kk => $vv):
                                                echo '<div class="form-inline">';
                                                if($model->c_action[$k] == 'show' || $model->c_action[$k] == 'hide'){
//                            echo $form->field($model,"c_field[{$k}]")->dropDownList( [] , ['style' => 'width:100px;','class' => 'c_field', 'data-selected' => isset($model->c_field[$k])?$model->c_field[$k]:0])->label(Yii::t('admin', 'Field'));
                                                    $allFields = \backend\models\AF::find()->where(['table' => $model->parent_table])->all();
                                                    echo $form->field($model,"c_field[{$k}][{$kk}]")->dropDownList(
                                                        array_merge(['0' => '-- choose --'],\yii\helpers\ArrayHelper::map($allFields, 'name', 'title')) ,
                                                        ['style' => 'width:200px;','class' => 'show_all_fields'])->label(Yii::t('admin', 'Table')
                                                    );
                                                }
                                                echo $form->field($model,"c_condition[{$k}][$kk]")->dropDownList( $c_condition , ['style' => 'width:150px;','class' => 'c_condition'])->label(Yii::t('admin', 'This field value'));
                                                echo $form->field($model,"c_value[{$k}][$kk]")->textInput( ['style' => 'width:150px;','class' => 'c_value'])->label(\Yii::t('admin','Value'));
                                                echo '
                                <a class="delete_field_choice remove-rule m-t-30" title="remove this rule"><i class="fa fa-minus-square fa-lg"></i></a>
                        </div>';
                                            endforeach;
                                            ?>
                                            <button class="btn btn-info btn-xs btn-mini add-rule" type="button"><i
                                                    class="fa fa-plus-square fa-lg"></i></button>
                                        </td>
                                        <td>
                                            <div class="extra_options form-inline">
                                                <?php
                                                //print_r($tables);die();
                                                switch ($model->c_action[$k]) {
                                                    case 'update_field':
                                                        echo $form->field($model, "c_table[{$k}]")->dropDownList(
                                                            array_merge(['0' => \Yii::t('admin', '-- choose --')], \yii\helpers\ArrayHelper::map($tables, 'table', 'table')),
                                                            ['style' => 'width:100px;', 'class' => 'c_table'])->label(Yii::t('admin', 'Table')
                                                        );

                                                        echo $form->field($model, "c_field[{$k}]")->dropDownList([], ['style' => 'width:100px;', 'class' => 'c_field', 'data-selected' => isset($model->c_field[$k]) ? $model->c_field[$k] : 0])->label(Yii::t('admin', 'Field'));
                                                        echo $form->field($model, "c_option[{$k}]")->textInput(['style' => 'width:150px;', 'class' => 'c_value'])->label(\Yii::t('admin', 'Value'));
                                                        break;
                                                    case 'prevent':
                                                        echo $form->field($model, "c_option[{$k}]")->textarea()->label(Yii::t('admin', 'Prevent Reason'));
                                                        break;
                                                    case 'sms':
                                                    case 'email':
                                                        echo $form->field($model, "c_template[{$k}]")->dropDownList(
                                                            ['0' => '-- choose --']+ \yii\helpers\ArrayHelper::map($templates, 'id', 'name'),
                                                            ['style' => 'width:100px;', 'class' => 'c_template'])->label(Yii::t('admin', 'Send This')
                                                        );
                                                        echo $form->field($model, "c_user[{$k}]")->dropDownList(['user' => 'User', 'other' => 'Other'], ['style' => 'width:100px;', 'class' => 'c_user'])->label(Yii::t('admin', 'To'));
                                                        echo $form->field($model, "c_option[{$k}]", ['options' => ['class' => 'hide check-hide']])->textInput(['style' => 'width:150px;', 'class' => 'c_option'])->label(\Yii::t('admin', 'Email'));
                                                        break;
                                                }
                                                ?>

                                            </div>
                                            <button type="button"
                                                    class="remove-condition btn btn-danger btn-xs btn-mini"><?= Yii::t('admin', 'Remove') ?></button>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                                ?>
                                </tbody>
                            </table>
                            <button class="btn btn-info btn-xs btn-mini" type="button"
                                    id="add_new_action"><?= Yii::t('admin', 'Add New Condition') ?></button>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>
</div>
<?php // END MineOptions Tabs
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>

<div class='p-a-10 p-b-0 bg-dark navbar-fixed-bottom'>
    <div class='container-fluid'>
        <div class='row-fluid'>
            <div class='col-xs-12'>
                <div class="pull-right">
                    <?php //($model->isNewRecord ? Yii::t('admin', 'Create & reload') : Yii::t('admin', 'Update & reload')), ['class' => $model->isNewRecord ? 'btn btn-success m-l-10 m-r-10' : 'btn btn-primary m-l-10 m-r-10', 'name' => 'button', 'value' => 'reload']?>
                    <button type='submit'
                            class='btn m-l-10 btn-lg btn3 m-b-0 <?php echo $model->isNewRecord ? 'btn btn-success' : 'btn btn-warning text-black'; ?>'
                            value='reload' name="button"><?php echo $model->isNewRecord ? Yii::t('admin', 'reload') : Yii::t('admin', 'reload'); ?></button>
                    <button type='submit'
                            class='btn  m-l-10 btn-lg btn4 m-b-0  <?php echo $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'; ?>'
                            value='update' name="button"><?php echo $model->isNewRecord ? Yii::t('admin', 'Create') : Yii::t('admin', 'Update'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12">



    <input type="radio" name="sql" value="Y" checked="checked" style="border: none; display: none;">

    <?php
    //BackendWidget::end();
    ActiveForm::end();

    $script = <<< JS

    function autoLoadFieldsValues(t){
         $.ajax({
            type: 'post',
            case: 'false',
            dataType: 'json',
            url: 'af-get-all-fields',
            data:  {'table':$(t).val()},
            success  : function(response) {
                if(true === response.success){
                    var sel = $(t).closest('.extra_options').find('.c_field');
                    sel.html('');
                    selected = sel.data('selected');
                    for(key in response.data) {
                        if(selected == key){
                            $('<option>').text(response.data[key]).val(key).prop( "selected", true ).appendTo(sel);
                        }else{
                            $('<option>').text(response.data[key]).val(key).appendTo(sel);
                        }
                    }
                }
            }
        });
    }

    function autoLoadUserValues (t){
        if($(t).val() == 'other'){
            $(t).closest('.extra_options').find('.check-hide').removeClass('hide');
        }else{
            $(t).closest('.extra_options').find('.check-hide').addClass('hide');
        }
    }
    function updateExtraFields (t){
         var content = $(t).closest('tr').find('.extra_options');
            content.html($('#'+$(t).val()+'_actions').html());

            var newCondition = jQuery("#table_conditional tr:last");
            var ReplaceWith = $(newCondition).find('select').attr("name").match(/\d+(?!.*\d+)/)*1 ;

            $(content).find('input[type="text"], select , textarea').each(function(){
                var attr_name = $(this).attr('name');
                // For some browsers, `attr` is undefined; for others,
                // `attr` is false.  Check for both.
                if (typeof attr_name !== 'undefined' && attr_name !== false){
                    var pattren = /AF\[c_\w+\]\[(\d+)\]/;
                    var string = $(this).attr('name');

                    $(this).attr('name', string.replace(pattren,  function(original , group){
                        return original.replace(group,ReplaceWith);
                    }));
                }
            });
    }

    jQuery(document).ready(function(){

        /**
        * Condition logic
        **/

        //toggle Conditional logic
        jQuery('#enable_conditional').click(function(){
            $( '#enable_conditional_content' ).slideToggle( 'slow', function() {});
        });

        //show Conditional logic on loading page if Conditional logic is enabled
        if (jQuery('#enable_conditional').is(':checked')){
             $('#enable_conditional_content' ).slideToggle( 'slow', function() {});
        }

        //create new condition
        jQuery('#add_new_action').click(function(){
            var newCondition = jQuery("#table_conditional tr:last");
            var ReplaceWith = $(newCondition).find('select').attr("name").match(/\d+(?!.*\d+)/)*1 + 1;

            jQuery('#table_conditional tr:last').after( $('#content_actions').html());
            var content = jQuery("#table_conditional tr:last");

            $(content).find('input[type="text"], select').each(function(){
                var attr_name = $(this).attr('name');
                // For some browsers, `attr` is undefined; for others,
                // `attr` is false.  Check for both.
                if (typeof attr_name !== 'undefined' && attr_name !== false){
                    var pattren = /AF\[c_\w+\]\[(\d+)\]/;
                    var string = $(this).attr('name');

                    $(this).attr('name', string.replace(pattren,  function(original , group){
                        return original.replace(group,ReplaceWith);
                    }));
                }
            });

            updateExtraFields(content.find('.actions'));

        });

        //remove existing condition
        jQuery('#table_conditional').on('click','.remove-condition' ,function(){
                var target = jQuery(this).closest('tr');
                target.addClass('danger');
                target.hide('slow', function(){ target.remove(); });
        });

        //adding rule
        jQuery('#table_conditional').on('click','.add-rule' ,function(){
            var newCondition = jQuery(this).closest('td').find('.form-inline:last').clone(true, true);
            var conditionCount1 = $(newCondition).find('input[type="text"]').attr("name").match(/\d+(?!.*\d+)/)*1 + 1;
            jQuery(this).closest('td').find('.form-inline:last').after(newCondition);

            $(newCondition).find('input[type="text"], select').each(function(){
                var attr_name = $(this).attr('name');

                // For some browsers, `attr` is undefined; for others,
                // `attr` is false.  Check for both.
                if (typeof attr_name !== 'undefined' && attr_name !== false)
                    $(this).attr('name', $(this).attr('name').replace(/\d+(?!.*\d+)/, conditionCount1) );
            });

        });

        //removing rule
        jQuery('#table_conditional').on('click','.remove-rule' , function(){
               jQuery(this).closest('div').remove();
        });

        //on action changed , display extra fields
        $('#table_conditional').on('change','.actions', function() {
           updateExtraFields(this);
           if($(this).val() == 'show' || $(this).val() == 'hide'){
                var added_show_all_fields = true;
                $(this).closest('tr').find('td:nth-child(2) .form-inline').prepend($('#show_fields_action').html());
            }
        });

        //on load , auto select values
        $( ".c_table" ).each(function( index ) {
            autoLoadFieldsValues(this);
        });
        $( ".c_user" ).each(function( index ) {
            autoLoadUserValues(this);
        });

        //on selecting table , display table fields
        $('#table_conditional').on('change','.c_table', function() {
            autoLoadFieldsValues(this);
        });

        //on selecting other option to send mail to , then show email field
        $('#table_conditional').on('change','.c_user', function() {
            autoLoadUserValues(this);
        });



        /**
        * Workflow engine
        **/

        //toggle workflow
        jQuery('#enable_workflow').click(function(){
            $( '#enable_workflow_content' ).slideToggle( 'slow', function() {});
        });

        //show Workflow on loading page if workflow is enabled
        if (jQuery('#enable_workflow').is(':checked')){
             $('#enable_workflow_content' ).slideToggle( 'slow', function() {});
        }

        //adding workflow
        jQuery('#table_workflow').on('click','.add-workflow' ,function(){
            var newCondition = jQuery(this).closest('.workflow-wrapper').find('.form-inline:last').clone(true, true);
            var conditionCount1 = $(newCondition).find('input[type="text"]').attr("name").match(/\d+(?!.*\d+)/)*1 + 1;
            jQuery(this).closest('.workflow-wrapper').find('.form-inline:last').after(newCondition);

            $(newCondition).find('input[type="text"], select').each(function(){
                var attr_name = $(this).attr('name');

                // For some browsers, `attr` is undefined; for others,
                // `attr` is false.  Check for both.
                if (typeof attr_name !== 'undefined' && attr_name !== false)
                    $(this).attr('name', $(this).attr('name').replace(/\d+(?!.*\d+)/, conditionCount1) );
            });

        });

        //removing workflow
        jQuery('#table_workflow').on('click','.remove-workflow' , function(){
               jQuery(this).closest('.form-inline').remove();
        });

        //adding workflow related
        jQuery('#table_workflow').on('click','.add-workflow-related' ,function(){
            var newCondition = jQuery(this).closest('.well').find('.form-inline:last').clone(true, true);
            var conditionCount1 = $(newCondition).find('input[type="text"]').attr("name").match(/\d+(?!.*\d+)/)*1 + 1;
            jQuery(this).closest('.well').find('.form-inline:last').after(newCondition);

            $(newCondition).find('input[type="text"], select').each(function(){
                var attr_name = $(this).attr('name');

                // For some browsers, `attr` is undefined; for others,
                // `attr` is false.  Check for both.
                if (typeof attr_name !== 'undefined' && attr_name !== false)
                    $(this).attr('name', $(this).attr('name').replace(/\d+(?!.*\d+)/, conditionCount1) );
            });

        });

        //removing workflow related
        jQuery('#table_workflow').on('click','.remove-workflow-related' , function(){
               jQuery(this).closest('div').remove();
        });


    });

    var DHTML = (document.getElementById || document.all || document.layers);

    function showLayer(name,visibility)
    {
        if (!DHTML) return;
        if (name)
        {
            var x = getObj(name);
            x.display = visibility ? '' : 'none';
        }
    }
    function getObj(name)
    {
      if (document.getElementById)
      {
        return document.getElementById(name).style;
      }
      else if (document.all)
      {
        return document.all[name].style;
      }
      else if (document.layers)
      {
        return document.layers[name];
      }
      else return false;
    }
    function switch_layers(type){
        switch (type){
            case 'text':
                showLayer('sql_query', 0);
                showLayer('values', 0);
                showLayer('size', 1);
                showLayer('textarea_size', 0);
                showLayer('text_default', 1);
                showLayer('image', 0);
                showLayer('custom', 0);
                showLayer('image_size', 0);
                showLayer('show_time', 0);
                back_sql_types();
                break;
            case 'date':
                showLayer('sql_query', 0);
                showLayer('values', 0);
                showLayer('size', 1);
                showLayer('textarea_size', 0);
                showLayer('text_default', 1);
                showLayer('image', 0);
                showLayer('custom', 0);
                showLayer('image_size', 0);
                showLayer('show_time', 1);
                back_sql_types();
                break;
            case 'editor':
                showLayer('sql_query', 0);
                showLayer('values', 0);
                showLayer('size', 1);
                showLayer('textarea_size', 0);
                showLayer('text_default', 1);
                showLayer('image', 0);
                showLayer('custom', 0);
                showLayer('image_size', 0);
                showLayer('show_time', 0);
                back_sql_types();
                break;
            case 'textarea':
                showLayer('sql_query', 0);
                showLayer('values', 0);
                showLayer('size', 0);
                showLayer('textarea_size', 1);
                showLayer('text_default', 1);
                showLayer('image', 0);
                showLayer('custom', 0);
                    showLayer('image_size', 0);
                showLayer('show_time', 0);
                clear_sql_types();
                break;
                break;
            case 'multi_select':
            case 'select':
                showLayer('sql_query', 1);
                showLayer('values', 1);
                showLayer('size', 1);
                showLayer('textarea_size', 0);
                showLayer('text_default', 0);
                showLayer('image', 0);
                showLayer('custom', 0);
                    showLayer('image_size', 0);
                showLayer('show_time', 0);
                clear_sql_types();
                break;
            case 'checkbox':
            case 'radio':
                showLayer('sql_query', 0);
                showLayer('values', 1);
                showLayer('size', 0);
                showLayer('textarea_size', 0);
                showLayer('text_default', 0);
                showLayer('image', 0);
                showLayer('custom', 0);
                    showLayer('image_size', 0);
                showLayer('show_time', 0);
                clear_sql_types();
                break;
            break;
            case  'multi_image':
            case 'image':
                showLayer('sql_query', 0);
                showLayer('values', 0);
                showLayer('size', 0);
                showLayer('textarea_size', 0);
                showLayer('text_default', 0);
                showLayer('image', 1);
                showLayer('custom', 0);
                    showLayer('image_size', 1);
                showLayer('show_time', 0);
                clear_sql_types();
                break;
            case 'custom':
                showLayer('sql_query', 0);
                showLayer('values', 0);
                showLayer('size', 0);
                showLayer('textarea_size', 0);
                showLayer('text_default', 0);
                showLayer('image', 0);
                showLayer('custom', 1);
                    showLayer('image_size', 0);
                showLayer('show_time', 0);
                clear_sql_types();
                break;
             case 'array':
                showLayer('sql_query', 0);
                showLayer('values', 0);
                showLayer('size', 1);
                showLayer('textarea_size', 0);
                showLayer('text_default', 1);
                showLayer('image', 0);
                showLayer('custom', 0);
                showLayer('image_size', 0);
                showLayer('show_time', 0);
                back_sql_types();
                break;
        }
        if (type == 'checkbox' || type == 'multi_select'){
            toggle_sql_type(false);
        } else {
            toggle_sql_type(true);
        }
    }



    frm = jQuery('#form-fields');


    elem = frm.find('input[name="AF[field_type]"]');
    for (i=0;i<frm.find('input[name="AF[field_type]"]').length;i++)
        if (frm.find('input[name="AF[field_type]"]')[i].checked)
            switch_layers(elem[i].value);
    elem = frm.find('input[name="AF[sql]"]');
    for (i=0;i<elem.length;i++)
        if (elem[i].checked) {
            showLayer('sql_type_l', elem[i].value);
        }

    function clear_sql_types(){

        elem = jQuery('#form-fields').find('input[name="AF[sql_type]"]:checked').val();
        if (elem != 1) {
            prev_opt = elem;
            elem = 1;

        }
    }
    function back_sql_types(){
        elem = jQuery('#form-fields').find('input[name="AF[sql_type]"]:checked').val();
        if ((elem == 1) && prev_opt)
            elem = prev_opt;
    }
    function toggle_sql_type(enable){
//        field = document.getElementById('form-fields').sql;
////        if (enable == true){
//            field[0].disabled = false;
////        } else {
////            field[0].disabled = true;
////            field[1].checked = true;
////        }
    }

JS;
    $this->registerJs($script, View::POS_END);
    ?>

    <script type="text/template" id="update_field_actions">
        <?php
        echo $form->field($model, "c_table[0]")->dropDownList(
            array_merge(['0' => '-- choose --'], \yii\helpers\ArrayHelper::map($tables, 'table', 'table')),
            ['style' => 'width:100px;', 'class' => 'c_table'])->label(Yii::t('admin', 'Table')
        );
        echo $form->field($model, "c_field[0]")->dropDownList([], ['style' => 'width:100px;', 'class' => 'c_field'])->label(Yii::t('admin', 'Field'));
        echo $form->field($model, "c_option[0]")->textInput(['style' => 'width:150px;', 'class' => 'c_value'])->label(\Yii::t('admin', 'Value'));
        ?>
    </script>

    <script type="text/template" id="prevent_actions">
        <?= $form->field($model, "c_option[0]")->textarea()->label(Yii::t('admin', 'Prevent Reason')); ?>
    </script>

    <script type="text/template" id="email_actions">
        <?php
        echo $form->field($model, "c_template[0]")->dropDownList(
            array_merge(['0' => '-- choose --'], \yii\helpers\ArrayHelper::map($templates, 'id', 'name')),
            ['style' => 'width:100px;', 'class' => 'c_template'])->label(Yii::t('admin', 'Send This')
        );
        echo $form->field($model, "c_user[0]")->dropDownList(['user' => \Yii::t('admin', 'User'), 'other' => \Yii::t('admin', 'Other')], ['style' => 'width:100px;', 'class' => 'c_user'])->label(Yii::t('admin', 'To'));
        echo $form->field($model, "c_option[{$k}]", ['options' => ['class' => 'hide check-hide']])->textInput(['style' => 'width:150px;', 'class' => 'c_option'])->label(\Yii::t('admin', 'Email'));
        ?>
    </script>

    <script type="text/template" id="content_actions">
        <tr>
            <td>
                <div class="form-inline">
                    <?= $form->field($model, "c_action[0]")->dropDownList($c_action_options, ['style' => 'width:150px;', 'class' => 'actions'])->label(Yii::t('admin', 'Do')); ?>
                    <?= $form->field($model, "c_if[0]")->dropDownList($c_if, ['style' => 'width:80px;', 'class' => 'if'])->label(Yii::t('admin', 'IF')); ?>
                    <label> <?= Yii::t('admin', 'Following conditions match:-') ?></label>
                </div>

            </td>
            <td>
                <div class="form-inline">
                    <?php
                    echo $form->field($model, "c_condition[0][0]")->dropDownList($c_condition, ['style' => 'width:150px;', 'class' => 'c_condition'])->label(Yii::t('admin', 'This field value'));
                    echo $form->field($model, "c_value[0][0]")->textInput(['style' => 'width:150px;', 'class' => 'c_value'])->label(\Yii::t('admin', 'Value'));
                    ?>
                    <a class="delete_field_choice remove-rule m-t-30" title="remove this rule"><i
                            class="fa fa-minus-square fa-lg"></i></a>
                </div>
                <button class="btn btn-info btn-xs btn-mini add-rule" type="button"><i
                        class="fa fa-plus-square fa-lg"></i></button>
            </td>
            <td>
                <div class="extra_options form-inline"></div>
                <button type="button"
                        class="remove-condition btn btn-danger btn-xs btn-mini"><?= Yii::t('admin', 'Remove') ?></button>
            </td>
        </tr>
    </script>
    <script type="text/template" id="show_fields_action">

        <?php

        $allFields = \backend\models\AF::find()->where(['table' => $model->parent_table])->all();
        echo $form->field($model,"c_field[0]")->dropDownList(
            array_merge(['0' => '-- choose --'],\yii\helpers\ArrayHelper::map($allFields, 'name', 'title')) ,
            ['style' => 'width:200px;','class' => 'show_all_fields'])->label(Yii::t('admin', 'Table')
        );
        ?>

    </script>
</div>
