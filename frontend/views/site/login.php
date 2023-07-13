<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\authclient\widgets\AuthChoice;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('app', 'Login');
//$this->params['breadcrumbs'][] = $this->title;
?>


    <div class="row margin-T40">
        <div class="page-login clearfix">

            <div class="col-xs-12 col-sm-6 loginInfo">
                <img src="<?= Yii::getAlias('@web'); ?>/themes/workflow/images/Login.png" alt=""/>
            </div>

            <div class="col-xs-12 col-sm-6 loginForm">

                <h1 class="clearfix"><span class="b-b b-dashed b-grey-dark color-primary"><?= Html::encode($this->title) ?></span></h1>
                <p class="color-asbestos clearfix"><?= Yii::t('app', 'Please fill out the following fields to login') ?>:</p>

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'email', ['options' => ['class' => 'dd']])->textInput(['class' => 'form-control']) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
                <div style="color:#999;margin:1em 0">
                    <?= Yii::t('app', 'If you forgot your password you can') ?> <?= Html::a('reset it', ['site/request-password-reset']) ?>
                    .
                </div>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-lg btn-primary', 'name' => 'login-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
                <?php // AuthChoice::widget([  'baseAuthUrl' => ['default/auth'] ]) ?>

            </div>
        </div>
    </div>



