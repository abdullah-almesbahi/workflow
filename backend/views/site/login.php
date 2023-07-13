<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\web\urlManager;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('admin','Login');
$this->params['breadcrumbs'][] = $this->title;
$model->rememberMe = 0;
?>
<div class="row login-container animated fadeInUp">

    <div class="col-md-7 col-md-offset-2 tiles white no-padding">

        <div class="p-t-30 p-l-40 p-b-20 xs-p-t-10 xs-p-l-10 xs-p-b-10">
            <img src="<?= Yii::$app->request->baseUrl?>/img/black-logo.png"  />
            <h2 class="normal"><?= Yii::t('admin','Sign in to OCM') ?></h2>
            <p><?= Yii::t('admin','Please fill out the following fields to login') ?></p>
        </div>
        <div class="tiles grey p-t-20 p-b-20 text-black">
            <?php $form = ActiveForm::begin(['id' => 'login-form' , 'options'=>['class'=>'animated fadeIn']]); ?>

                <div class="row form-row m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
                    <div class="col-md-6 col-sm-6 ">
                        <?= $form->field($model, 'username', [
                                'inputOptions' => [
                                    'placeholder' => Yii::t('admin','User Name'),
                                ]
                            ])->label(false); ?>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <?= $form->field($model, 'password',[
                            'inputOptions' => [
                                'placeholder' => Yii::t('admin','Password'),
                            ]
                        ])->label(false)->passwordInput() ?>
                    </div>
                </div>
                <div class="row p-t-10 m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
                    <div class="control-group col-md-7">
                            <?= $form->field($model, 'rememberMe',[
                                'options' => [
                                    'class' => 'checkbox check-primary'
                                ],
                                'template' => '{input}{label}{hint}{error}'

                            ])->checkbox([],false)->label(Yii::t('admin','Keep me reminded')) ?>
                    </div>
                    <div class="col-md-5">
                        <a href="#" class="forgot_password"><?= Yii::t('admin','Trouble login in?'); ?></a>&nbsp;&nbsp;
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-cons', 'name' => 'login-button']) ?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
            <?php
                $form = ActiveForm::begin([
                    'id' => 'request-password-reset-form' ,
                    'options'=>['class'=>'animated fadeIn','style' => 'display:none;'],
                    'method' => 'get',
                    'action' =>Yii::$app->urlManagerFrontEnd->createUrl('site/request-password-reset'),
                ]); ?>
                <div class="row form-row m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
                    <div class="col-md-12"><p class="m-b-10"><b><?= Yii::t('admin','Please fill out your email. A link to reset password will be sent there.'); ?></b></p></div>
                </div>
                <div class="row form-row m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
                    <div class="col-md-9">
                        <?= $form->field($prr_model, 'email', [
                            'inputOptions' => [
                                'placeholder' => Yii::t('admin','Email'),
                            ]
                        ])->label(false); ?>

                    </div>
                    <div class="col-md-3">
                        <?= Html::submitButton('Send', ['class' => 'btn btn-info btn-cons', 'name' => 'login-button']) ?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php
$this->registerJs("

    jQuery(document).ready(function() {
        jQuery('.forgot_password').click(function () {
            jQuery('#request-password-reset-form').show();
        })
    });

", View::POS_READY);

