<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

//use backend\components\ActionColumn;

/* @var $this yii\web\View */
$this->title = 'Billing';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <table id="payments2" class="table">

        <tbody>
        <tr>
            <th class="add_funds top_left" style="width: 181px"><span
                    id="ctl00_membersContent_Label2">Your Points</span>&nbsp;</th>
            <th class="top_right"></th>
        </tr>


        <tr>

            <td style="width: 181px"><span id="ctl00_membersContent_lblYourBalance">Current Balance</span></td>
            <td>$<span id="ctl00_membersContent_balance" style="font-weight:bold;">0.0000</span></td>
        </tr>
        <tr>
            <td style="width: 181px"><span id="ctl00_membersContent_lblPointsAvailable">Your Points</span></td>
            <td><span id="ctl00_membersContent_points">0</span> = $<span id="ctl00_membersContent_dollars">0</span><br>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="submit" name="ctl00$membersContent$points_button" value="Add to your account"
                       id="ctl00_membersContent_points_button" disabled="disabled" class="aspNetDisabled button">
            </td>
        </tr>

        </tbody>
    </table>


    <h2>Credit Card preference</h2>
    <div class="credit-card-preference">
        <div class="ccp-m"></div>
        <input id="enable_autopay" type="radio" name="CRUD[enable_autopay]" value="1" checked="checked">
        <label for="enable_autopay">Auto Pay : Withdraw from my credit card as soon as the order is prepared</label>
        <br>
        <input id="disable_autopay" type="radio" name="CRUD[enable_autopay]" value="0">
        <label for="disable_autopay">Post Pay : Pay manually after the order is prepared</label>
        <br>

    </div>


    <div align=center>
        <span id="siteseal"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=NMCDzZPuEcQEz4iqYyQqGaQ9DCwfx6KgXrWjpC9z0j7T9TsEAowckLyrwO"></script></span>
        <!-- (c) 2005, 2011. Authorize.Net is a registered trademark of CyberSource Corporation --> <div class="AuthorizeNetSeal"> <script type="text/javascript" language="javascript">var ANS_customer_id = "40c258bb-4359-4d2a-8fd3-858fcfbb1ad9";</script> <script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script></div>
    </div>


        <h2>Credit Card on file</h2>
    <div class="credit-card">
        There is no credit card on file. To add a new credit card, or to update information
        <a href="" class="btn btn-primary">Click Here</a>
    </div>


    <h2>Wire Transfer</h2>
    <div class="wire-transfer">
        <?php if(empty($user->bank_acct)): ?>
            There is no wire transfer details on file. To add wire transfer details, click on
        <?php else: ?>
        Bank account on file <strong>Your Bank Name : <?= $user->bank_from?> - Your Account # : <?= $user->bank_acct ?></strong>
        <?php endif;?>
        <button type="button" class="btn btn-primary wire-update-toggle">Update Information</button>
        <div class="wire-details" style="display: none">
            <div class="wd-m"></div>
            <?php
            $bank_from = [
                'Rajhi Bank' => 'Al-Rajhi Bank',
                'National Commercial Bank' => 'National Commercial Bank',
                'Riyad Bank' => 'Riyad Bank',
                'Samba Bank' => 'Samba Bank',
                'Other' => 'OtherBank',
            ];
            $bank_to = [
                'RAJHI' => 'Rajhi Bank - Digital Commerce Ltd',
                'NCB' => 'National Commercial Bank - Digital Commerce Ltd',
                'SAMBA' => 'SAMBA - Digital Commerce Ltd',
            ];
            ?>
            <?php $form = \kartik\form\ActiveForm::begin([
                'id' => 'form-wire',
                'action' => \yii\helpers\Url::to(['account/wire-transfer']),
            ]);?>
            <?= $form->field($user,"bank_from")->dropDownList($bank_from)->label(Yii::t('app', 'Transfer From')); ?>
            <?= $form->field($user,"bank_to")->dropDownList($bank_to)->label(Yii::t('app', 'Transfer To')); ?>
            <?= $form->field($user, 'bank_acct')->textInput()->label('Account Number') ?>
            <?= $form->field($user, 'bank_name')->textInput()->label('Account Holder Name') ?>
            <input type="hidden" name="id" value="<?=$user->id?>" />
            <button class="btn btn-primary submit-wire" type="button">Save</button>
            <?php \kartik\form\ActiveForm::end();?>
        </div>
    </div>


</div>

<?php
$url = \yii\helpers\Url::to(['account/billing']);
$script = <<< JS

    jQuery(document).ready(function(){
        //on active/deactive auto pay
        $('#enable_autopay,#disable_autopay').change(function() {
            $.ajax({
                'url': '$url',
                'type': 'post',
                'dataType': 'json',
                'data': {
                    'User[enable_autopay]': $(this).val()
                },
                success: function (data) {
                    if(true == data.access){
                        $('.ccp-m').removeClass('alert-danger').addClass('alert').addClass('alert-success').html(data.message);
                    }else{
                        $('.ccp-m').removeClass('alert-success').addClass('alert').addClass('alert-danger').html(data.message);
                    }
                }
            });
        });

        //toggle show/hide wire transfer form on click
        $('.wire-update-toggle').click(function(){
            $('.wire-details').toggle();
        });

        //on submit wire transfer form
        $('.submit-wire').on('click', function() {
            var form = $("#form-wire");
            if(form.find('.has-error').length) {
                    return false;
            }
            $.ajax({
                url: form.attr('action'),
                type: 'post',
                data: form.serialize(),
                success: function(data) {
                    if(true == data.access){
                        $('.wd-m').removeClass('alert-danger').addClass('alert').addClass('alert-success').html(data.message);
                    }else{
                        $('.wd-m').removeClass('alert-success').addClass('alert').addClass('alert-danger').html(data.message);
                    }
                }
            });
        });
    });

JS;
$this->registerJs($script, \yii\web\View::POS_END);
