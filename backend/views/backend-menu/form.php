<?php

use backend\widgets\BackendWidget;
use kartik\helpers\Html;
use kartik\icons\Icon;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;

    /**
     * @var $this \yii\web\View
     * @var $model \backend\models\BackendMenu
     */
    $this->title = Yii::t('admin', 'Backend menu item edit');

    $this->params['breadcrumbs'][] = ['url' => ['backend-menu/index'], 'label' => Yii::t('admin', 'Backend menu items')];
    if (($model->parent_id > 0) && (null !== $parent = \backend\models\BackendMenu::findById($model->parent_id))) {
        $this->params['breadcrumbs'][] = ['url' => ['backend-menu/index', 'id' => $parent->id, 'parent_id' => $parent->parent_id], 'label' => $parent->name];
    }
    $this->params['breadcrumbs'][] = $this->title;

?>

<div id="widget-grid" class="row">

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

<?php $form = ActiveForm::begin(['id' => 'backend-menu-form']); ?>

<?php $this->beginBlock('submit'); ?>
<div class="form-group no-margin">
    <?=
    Html::submitButton(
        Icon::show('save') . Yii::t('admin', 'Save'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
    ) ?>
</div>
<?php $this->endBlock('submit'); ?>




            <?php BackendWidget::begin(['title'=> Yii::t('admin', 'Backend menu item'), 'icon'=>'tree', 'footer'=>$this->blocks['submit']]); ?>
                
                <?= $form->field($model, 'name') ?>

                <?= $form->field($model, 'route') ?>                

                <?= $form->field($model, 'icon') ?>

                <?= $form->field($model, 'added_by_ext') ?>

                <?= $form->field($model, 'css_class') ?>

                <?= $form->field($model, 'rbac_check') ?>
                
                <?= $form->field($model, 'sort_order') ?>

            <?php BackendWidget::end(); ?>



<?php ActiveForm::end(); ?>
    </div>


</div>
