<?php
use yii\helpers\Html;
if(sizeof($model->settings) > 0) :
    foreach($model->settings as $key => $value) :
        if(!is_bool($value) && $value != '1' && $value != '0') :
            ?>
            <div class="form-group">
                <label><?= $key; ?></label>
                <?= Html::input('text', 'Module[settings]['.$key.']', $value, ['class' => 'form-control']); ?>
            </div>
        <?php else : ?>
            <div class="checkbox check-primary field-af-enable_condition">
                <?= Html::checkbox('Module[settings]['.$key.']', $value, ['uncheck' => 0,'id' => $key])?>
                <label class="control-label" for="<?= $key ?>"><?= $key ?></label>
                <div class="help-block"></div>
            </div>
        <?php
        endif;
    endforeach;
else :
    echo Yii::t('admin', 'module doesn`t have any settings.');
endif;
?>
<a href="/admin/modules/restoresettings/<?php //$model->module_id ?>" class="pull-right text-warning"><i class="glyphicon glyphicon-flash"></i> <?= Yii::t('admin', 'Restore default settings') ?></a>