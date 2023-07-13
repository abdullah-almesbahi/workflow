<?php
use kartik\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
$this->title = 'In your Account';
?>
<div class="RequestPictureMsg"></div>
<div class="site-index">
    <?= $this->render('@app/themes/default/crud/index', [
        'dataProvider' => $dataProvider,
        'af_columns' => $af_columns,
        'button' => false,
        'title' => false,
    ]) ?>

    <input type="submit" name="btnPictureRequest" value="Request Picture"
           onclick="if(!confirm('Are you sure? you will be charged $3 or $4 per picture based on your plan')) return false;"
           id="RequestPicture" class="btn btn-primary">

    <?php $form = \kartik\form\ActiveForm::begin([
        'id' => 'form-request',
        'action' => \yii\helpers\Url::to(['order/update'])
    ]);?>
    <div class="panel panel-default m-t-20">
        <div class="panel-heading separator" style="background-color: #f15a24">
            <div class="panel-title" style="color: white;"><span
                    id="ctl00_membersContent_Label4">Request Shipping</span></div>
        </div>
        <div class="panel-body p-t-20">
            <div class="table-responsive">
                <table class=" table-striped table-condensed ">
                    <tr>
                        <td style="width: 330px">

                            Shipping Method

                        </td>
                        <td>

                            <div class="radio radio-primary">
                                <span style="color: #FF0000; font-weight: 700">
                                    <input id="SV_type" type="radio" name="Order[service_type]" value="SV" checked="checked"/>
                                    <label for="SV_type">DHL ExpressWorldwide</label>
                                    <span id="SV_price"></span>
                                </span>
                                <br/>
                                <input id="FDX_type" type="radio" name="Order[service_type]" value="FDX"/>
                                <label for="FDX_type">FedEx Priority</label>
                                <span id="FDX_price"></span>

                                <br/>
                                <input id="FDX_IE_type" type="radio" name="Order[service_type]" value="FDX_IE"/>
                                <label for="FDX_IE_type">FedEx Economy</label>
                                <span id="FDX_IE_price"></span>

                                <br/>
                                <input id="EX_type" type="radio" name="Order[service_type]" value="EX"/>
                                <label for="EX_type">Aramex Economy</label>
                                <span id="EX_price"></span>

                            </div>

                            <input type="submit" name="EstimateCharges" value="Estimate Shipping Charges" onclick="" id="EstimateCharges" class="btn btn-primary"/><br /><br />
                            <div class="alert alert-info">Note: This is only an estimate. Actual charges maybe higher or lower due to consolidation and dimensional weight.</div>

                        </td>
                    </tr>
                    <tr>
                        <td style="width: 330px">
                            <span>Coupon Code</span>

                        </td>
                        <td>
                            <input name="Order[coupon]" type="text" id="coupon" class="coupon_text" style="width:198px;"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 330px">
                            <span>Options</span></td>
                        <td>
                            <div class="checkbox check-primary">
                                <input id="urgentShipping" type="checkbox" name="Order[other_fee]" value="5" />
                                <label for="urgentShipping">Urgent Processing $5</label>
                                <em><a id="HyperLink3" href="javascript:popUp(&#39;ShowHelp.aspx?id=urgent&#39;)">(What isthis?)</a></em>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 330px">
                            <span>Insurance</span></td>
                        <td>
                            <div class="checkbox check-primary">
                                <input id="insureShipment" type="checkbox" name="Order[insurance]" value="1" checked="checked" />
                                <label for="insureShipment">Add insurance to my shipment</label>

                                <em>
                                    <span>
                                        World Ship will arrange for insurance on your behalf in case of lost/damaged items. if you do not request insurance, and your item is lost/damaged, you won't be compensated. Insurance is subject to UPS Capital terms & conditions.
                                    </span>
                                </em>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td style="width: 330px">
                            <span id="ctl00_membersContent_lblPackagingOptions">Packaging Options</span>
                            <em><a id="ctl00_membersContent_HyperLink6" href="javascript:popUp(&#39;ShowHelp.aspx?id=PackgingOptions&#39;)">(What isthis?)</a></em>
                        </td>
                        <td>
                            <table style="width: 100%">
                                <tr>
                                    <td>
                                        <span class="checkbox check-primary">
                                            <input id="DiscardShoe" type="checkbox" name="Order[is_discard_shoe]" value="1" checked="checked"/>
                                            <label for="DiscardShoe">Discard shoe box(s)</label>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="checkbox check-primary">
                                            <input id="KeepOriginal" type="checkbox" name="Order[is_keep_original]" value="1" />
                                            <label for="KeepOriginal">Keep original box(s)</label>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="checkbox check-primary">
                                            <input id="FragileSticker" type="checkbox" name="Order[is_fragile_sticker]" value="1" />
                                            <label for="FragileSticker">Fragile Sticker ($1)</label>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="checkbox check-primary">
                                            <input id="ExtraPacking" type="checkbox" name="Order[is_extra_packing]" value="1" />
                                            <label for="ExtraPacking">Extra packing ($3)</label>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Ship To</span>
                        </td>
                        <td>
                            <?php
                                $address = \frontend\models\Address::find()->where(['customer_id' => Yii::$app->user->id])->all();
                                $data = array_merge(['0'=>'My Address'],\yii\helpers\ArrayHelper::map($address,'id','nickname'));
                                echo Html::dropDownList('ship_to',null, $data);


                            ?>
                            <em><a id="hyperLink1" href="javascript:popUp(&#39;ShowHelp.aspx?id=addressbook&#39;)">(What is this?)</a></em>


                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">


                        </td>
                    </tr>
                </table>

            </div>

            <div class="m-t-20">
                <input type="hidden" name="shipment_ids" id="shipment_ids" />
                <input type="button" name="process" value="Submit Shipping Request" id="process" class="btn btn-primaryo"/>
            </div>
        </div>
    </div>
    <?php \kartik\form\ActiveForm::end(); ?>
    <div class="onHold-msg"></div>
    <h2>Shipments On Hold</h2>
    <?= $this->render('@app/themes/default/crud/index', [
        'dataProvider' => $onHoldDataProvider,
        'af_columns' => $onHoldAf_columns,
        'button' => false,
        'title' => false,
    ]) ?>
    <input type="submit" name="btnPictureRequest" value="Return Packages"

           id="btnPictureRequest" class="btn btn-primary">
    <input type="submit" name="btnPictureRequest" value="Request Picture"
           onclick="if(!confirm('Are you sure? you will be charged $3 or $4 per picture based on your plan')) return false;"
           id="RequestPicture2" class="btn btn-primary">
    <input type="submit" name="btnPictureRequest" value="Abandon shipment"
           onclick="if(!confirm('Are you sure ? Your order will process immediately. Once your shipment is abandoned it cannot be recovered.')) return false; ;"
           id="abandon" class="btn btn-primary">
</div>
<?php
$url = \yii\helpers\Url::to(['site/estimated-shipping-charges']);
$url2 = \yii\helpers\Url::to(['site/request-picture']);
$urlAbandon = \yii\helpers\Url::to(['site/dispose']);
$script = <<< JS
    function productSubmit(id){
        var form = $("#"+id);
        if(form.find('.has-error').length) {
                return false;
        }
        $.ajax({
                url: form.attr('action'),
                type: 'post',
                data: form.serialize(),
                success: function(data) {
                        // do something ...t
                }
        });
    }

    jQuery(document).ready(function(){
        $('#EstimateCharges').on('click', function() {
            var items =  $('.grid-view').yiiGridView('getSelectedRows');
            if (items.length) {
                $.ajax({
                    'url': '$url',
                    'type': 'post',
                    //'dataType': 'json',
                    'data': {
                        'items': items
                    },
                    success: function (data) {
                        $.each(data, function( index, value ) {
                            if(value == 'N/A'){
                                $("#"+index+"_price").html(value);
                            }else{
                                $("#"+index+"_price").html("$ "+value);
                            }

                        });
                    }
                });
            }
        });

        $('#RequestPicture').on('click', function() {
            var items =  $('.grid-view').yiiGridView('getSelectedRows');
            if (items.length) {
                $.ajax({
                    'url': '$url2',
                    'type': 'post',
                    //'dataType': 'json',
                    'data': {
                        'items': items
                    },
                    success: function (data) {
                        if(true == data.access){
                            $('.RequestPictureMsg').addClass('alert').addClass('alert-success').html(data.message);
                        }
                    }
                });
            }
        });

        $('#RequestPicture2').on('click', function() {
            var items =  $('.grid-view').yiiGridView('getSelectedRows');
            if (items.length) {
                $.ajax({
                    'url': '$url2',
                    'type': 'post',
                    //'dataType': 'json',
                    'data': {
                        'items': items
                    },
                    success: function (data) {
                        if(true == data.access){
                            $('.onHold-msg').addClass('alert').addClass('alert-success').html(data.message);
                        }
                    }
                });
            }
        });

        $('#abandon').on('click', function() {
            var items =  $('.grid-view').yiiGridView('getSelectedRows');
            if (items.length) {
                $.ajax({
                    'url': '$urlAbandon',
                    'type': 'post',
                    //'dataType': 'json',
                    'data': {
                        'items': items
                    },
                    success: function (data) {
                        if(true == data.access){
                            $('.onHold-msg').addClass('alert').addClass('alert-success').html(data.message);
                        }
                    }
                });
            }
        });

        $('#process').on('click', function() {
            var items =  $('.grid-view').yiiGridView('getSelectedRows');
            if (items.length) {
                $("#shipment_ids").val(items);
                $("#form-request").submit();
            }
        });

    });

JS;
$this->registerJs($script, View::POS_END);
