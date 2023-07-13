<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

$this->title = 'Rates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <!-- Form Section -->
        <?php $form = ActiveForm::begin(['id' => 'rate']); ?>
        <div class="col-xs-12 col-md-6">
            <header class="head-titleLeft m-b-10 p-b-20"><h3>Calculate your shipping</h3></header>


            <div class="form-group marginB20 clearfix">
                <div class="form-group">
                    <label class="col-sm-2 control-label fontprimary font-18">Country</label>
                    <?= \kartik\select2\Select2::widget([
                        'name' => "country",
                        'data' => \yii\helpers\ArrayHelper::map(
                            (new \yii\db\Query())
                                ->select(['country_zone', 'country_name'])
                                ->from('country')
                                ->all()
                            , 'country_zone' , 'country_name'),
                        'options' => ['placeholder' =>  Yii::t('app', 'Select Country.')],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]);?>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label fontprimary font-18">Plan</label>
                    <?= \kartik\helpers\Html::dropDownList('plan',null,
                        \yii\helpers\ArrayHelper::map(
                            (new \yii\db\Query())
                            ->select(['id', 'title'])
                            ->from('plan')
                            ->all()
                        , 'id' , 'title')
                        ); ?>
                </div>


                <div class="form-group marginB20 clearfix">
                    <label class="col-sm-2 control-label fontprimary font-18">Weight</label>

                    <div class="col-sm-3">
                        <input name="weight" type="text" value="1" id="weight" class="form-control">
                    </div>
                    <div class="col-sm-3">
                        <div class="selecter  closed" tabindex="0">
                            <select name="w_unit" id="w_unit" class="vidaSelect selecter-element" tabindex="-1">
                                <option selected="selected" value="lb">LB</option>
                                <option value="kg">KG</option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="form-group marginB20 clearfix">
                    <header class="head-titleLeft marginB10 paddingB20"><h3>Dimensions</h3></header>

                    <label class="col-sm-2 control-label fontprimary font-18">Length</label>

                    <div class="col-sm-3">
                        <input name="w" type="text" value="0" id="w"
                               class="form-control">
                    </div>
                </div>

                <div class="form-group marginB20 clearfix">

                    <label class="col-sm-2 control-label fontprimary font-18">Width</label>

                    <div class="col-sm-3">
                        <input name="l" type="text" value="0" id="l" class="form-control">
                    </div>
                </div>

                <div class="form-group marginB20 clearfix">

                    <label class="col-sm-2 control-label fontprimary font-18">Height</label>

                    <div class="col-sm-3">
                        <input name="h" type="text" value="0" id="h" class="form-control">
                    </div>
                </div>


                <div class="form-group marginB20 clearfix">
                    <label class="col-sm-2 control-label fontprimary font-18">Unit</label>

                    <div class="col-sm-6">
                        <div class="selecter  open" tabindex="0">
                            <select name="d_unit" id="d_unit" class="vidaSelect selecter-element" tabindex="-1">
                                <option selected="selected" value="inch">inch</option>
                                <option value="cm">Centimeter</option>

                            </select>
                        </div>
                    </div>
                </div>

                <input type="submit" name="btnCalculateRate" value="Calculate" id="btnCalculateRate" class="btn btn-warning btn-lg fontprimary">

            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <!-- END Form Section -->

        <div class="col-xs-12 col-md-6">

            <ul id="shipping_rates_calc" class="list-group">
                <li class="list-group-item"><span class="badge badge-default"><span
                            id="ctl00_ContentPlaceHolder1_express_rate"
                            style="text-decoration:none;"><?php if(is_numeric($result['SV']))echo "$"; ?> <?= $result['SV'];?></span></span>
                    DHL Express Worldwide <br>
                    <em>3-5 business days after shipping. </em></li>

                <li class="list-group-item"><span class="badge badge-default"><span
                            id="ctl00_ContentPlaceHolder1_fedex_rate"><?php if(is_numeric($result['FDX']))echo "$"; ?> <?= $result['FDX'];?></span></span>
                    FedEx Priority <br>
                    <em>3-5 business days after shipping. </em></li>

                <li class="list-group-item"><span class="badge badge-default"><span
                            id="ctl00_ContentPlaceHolder1_fedex_ie_rate"><?php if(is_numeric($result['FDX_IE']))echo "$"; ?> <?= $result['FDX_IE'];?></span></span>
                    FedEx Economy <br>
                    <em>7-10 business days after shipping. </em></li>

                <li class="list-group-item"><span class="badge badge-default"><span
                            id="ctl00_ContentPlaceHolder1_economy_rate"><?php if(is_numeric($result['EX']))echo "$"; ?> <?= $result['EX'];?></span></span>
                    Aramex Economy <br>
                    <em>7-10 business days after shipping. </em></li>
            </ul>

        </div>
    </div>
</div>