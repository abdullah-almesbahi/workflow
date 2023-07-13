<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1><?= Html::encode($this->title) ?></h1>




<style>
    .wizard-steps {
        display: block;
        list-style: none outside none;
        padding: 0;
        position: relative;
        width: 100%;
    }
    .wizard-steps li {
        display: block;
        float: left;
        max-width: 25%;
        min-width: 25%;
        text-align: center;
        padding-left: 0;
    }
    .wizard-steps li:before {
        border-top: 6px solid #55606E;
        content: "";
        display: block;
        font-size: 0;
        overflow: hidden;
        position: relative;
        top: 13px;
        right: 1px;
        width: 100%;
        z-index: 1;
    }
    .wizard-steps li:first-child:before {
        left: 50%;
        max-width: 50%;
    }
    .wizard-steps li:last-child:before {
        max-width: 50%;
        width: 50%;
    }
    .wizard-steps li.complete .step {
        background-color: #0aa699;
        padding: 1px 6px;
        border: 4px solid #55606e;
    }
    .wizard-steps li .step {
        background-color: #d1dade;
        border-radius: 32px 32px 32px 32px;
        color: #ffffff;
        display: inline;
        font-size: 15px;
        font-weight: bold;
        line-height: 12px;
        padding: 4px 9px;
        position: relative;
        text-align: center;
        z-index: 2;
        transition: all 0.2s linear 0s;
        top: -10px;
    }
    .wizard-steps li .step i {
        font-size: 10px;
        font-weight: normal;
        position: relative;
        top: -1.5px;
    }
    .wizard-steps li .title {
        color: #B1BCC5;
        display: block;
        font-size: 13px;
        line-height: 15px;
        max-width: 100%;
        position: relative;
        table-layout: fixed;
        text-align: center;
        top: 20px;
        word-wrap: break-word;
        z-index: 104;
    }
    .wizard-steps a:hover,
    .wizard-steps a:active,
    .wizard-steps a:focus {
        text-decoration: none;
    }
    .wizard-steps li.active .step,
    .wizard-steps li.active.complete .step {
        background-color: #0090d9;
        color: #ffffff;
        font-weight: bold;
        padding: 10px 15px;
        border: none;
        font-size: 16px;
    }
    .wizard-steps li.complete .title,
    .wizard-steps li.active .title {
        color: #2B3D53;
    }
    .step-content {
        margin-left: 60px;
        margin-top: 40px;
    }
    .step-content .step-pane {
        display: none;
        min-height: 267px;
    }
    .step-content .active {
        display: block;
    }
    .wizard-actions {
        display: block;
        list-style: none outside none;
        padding: 0;
        position: relative;
        width: 100%;
    }
    .wizard-actions li {
        display: inline;
    }
    .nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus {
         color: #fff;
         background-color: inherit;
    }
    .nav-pills > li + li {
        margin-left: 0px;
    }
    .nav > li > a:hover, .nav > li > a:focus {
        text-decoration: none;
        background-color: inherit;
    }
</style>
<script type="text/javascript">

</script>
<div class="row">
    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <div id="rootwizard" class="col-md-12">
            <div class="form-wizard-steps">
                <ul class="wizard-steps">
                    <li> <a href="#tab1" data-toggle="tab"> <span class="step">1</span> <span class="title">Basic information</span> </a> </li>
                    <li> <a href="#tab2" data-toggle="tab"> <span class="step">2</span> <span class="title">Shipping Address</span> </a> </li>
                    <li> <a href="#tab3" data-toggle="tab"> <span class="step">3</span> <span class="title">Service</span> </a> </li>
                    <li> <a href="#tab4" data-toggle="tab"> <span class="step">4</span> <span class="title">Payment </span> </a> </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="tab-content transparent">
                <div class="tab-pane" id="tab1">
                    <br>
                    <h4 class="semi-bold">Step 1 - <span class="light">Basic Information</span></h4>
                    <br>
                    <?= $form->field($model, 'first_name') ?>
                    <?= $form->field($model, 'last_name') ?>
                    <?= $form->field($model, 'username') ?>
                    <?= $form->field($model, 'email') ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <div class="form-group">
                        <?php //Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    </div>

                </div>
                <div class="tab-pane" id="tab2"> <br>
                    <h4 class="semi-bold">Step 2 - <span class="light">Shipping Address</span></h4>
                    <br>
                    <?= $form->field($model, 'address_1') ?>
                    <?= $form->field($model, 'address_2') ?>
                    <?= $form->field($model, 'country') ?>
                    <?= $form->field($model, 'city') ?>
                    <?= $form->field($model, 'zip') ?>
                    <?= $form->field($model, 'state') ?>
                    <?= $form->field($model, 'mobile') ?>
                    <?= $form->field($model, 'phone') ?>
                    <?= $form->field($model, 'lang') ?>
                </div>
                <div class="tab-pane" id="tab3"> <br>
                    <h4 class="semi-bold">Step 3 - <span class="light">Service</span></h4>
                    <br>
                    <?= $form->field($model, 'plan') ?>
                    <?= $form->field($model, 'coupon') ?>
                    <?= $form->field($model, 'method') ?>
                </div>
                <div class="tab-pane" id="tab4"> <br>
                    <h4 class="semi-bold">Step 4 - <span class="light">Payment</span></h4>
                    <br>
                </div>
                <ul class=" wizard wizard-actions">
                    <li class="previous first" style="display:none;"><a href="#">First</a></li>
                    <li class="previous"><a href="#" class="btn btn-primary">Previous</a></li>
                    <li class="next"><a href="#" class="btn btn-primary">Next</a></li>
                    <li class="next finish" style="display:none;"><button type="submit" class="btn btn-primary">Finish</button></li>
                </ul>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<?php

$script = <<< JS
    jQuery(document).ready(function(){
        $('#rootwizard').bootstrapWizard({onTabShow: function(tab, navigation, index) {
		var total = navigation.find('li').length;
		var current = index+1;

		// If it's the last tab then hide the last button and show the finish instead
		if(current >= total) {
			$('#rootwizard').find('.wizard-actions .next').hide();
			$('#rootwizard').find('.wizard-actions .finish').show();
			$('#rootwizard').find('.wizard-actions .finish').removeClass('disabled');
		} else {
			$('#rootwizard').find('.wizard-actions .next').show();
			$('#rootwizard').find('.wizard-actions .finish').hide();
		}

	}});
    });

JS;
$this->registerJs($script, \yii\web\View::POS_END);

