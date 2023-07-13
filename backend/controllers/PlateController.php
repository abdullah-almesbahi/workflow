<?php

namespace backend\controllers;

use backend\models\Plate;
use backend\models\Plateoption;
use backend\models\Model;
use backend\models\Stock;
use backend\widgets\BackendWidget;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

class PlateController extends CrudController
{
    public static $modelClass = '\backend\models\Plate';
    public static $tableName = 'plate';

    var $dynamicViewMode = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['delete','remove-all'],
                        'roles' => ['delete plate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index','update','upload','remove','save-info'],
                        'roles' => ['view plate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['getAfTree','reorder-af-tree','af','af-update','af-delete','af-order','af-get-all-fields'],
                        'roles' => ['developer'],
                    ],
                ],
            ],
        ];
    }


    /**
     * First registration form
     * If update is successful, the browser will be redirected to the same page with flash message.
     * @param null $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionUpdate($id = null, $redirect = ['index'])
    {
        $model = null;
        if (null === $id || 0 === $id) {
        } else {
            $model = $this->findModel($id);
        }


        if ($id > 0 && in_array($model->status , ['PlateWorkflow/secretary','PlateWorkflow/reviewSecretary','PlateWorkflow/pendingExecution','PlateWorkflow/executeChecked','PlateWorkflow/accountant','PlateWorkflow/designer','PlateWorkflow/approveDesign','PlateWorkflow/modifyDesign','PlateWorkflow/executeNote','PlateWorkflow/executed','PlateWorkflow/accountant'])):
            //check from stock if there are stocks to use using ajax
            $this->alamat($model);

            //display Dynamic related table & save it
            $this->dynamicViewMode = in_array($model->status , ['PlateWorkflow/pendingExecution' , 'PlateWorkflow/accountant','PlateWorkflow/designer','PlateWorkflow/approveDesign','PlateWorkflow/modifyDesign','PlateWorkflow/executeNote','PlateWorkflow/executed','PlateWorkflow/accountant'])?true:false;
            $this->relatedDynamic($model,Stock::className());
        endif;
        return parent::actionUpdate($id, $redirect);
    }




    public function alamat($model){
        $postData = Yii::$app->request->post('Stock');
        if($postData !== null && is_array($postData)) {
//            print_r($postData);die();
            foreach($postData as $k => $v){
                if($v['source'] == 'stock' && isset($v['id'])){
                    $stock = Stock::find()->where(['id' => $v['id']])->one();
                    if(!is_null($stock)){
                        $stock->plate_id = $model->id;
                        $p = Plate::findOne($model->id);
                        $stock->old_offer_id = $p->offer_id;
                        $stock->status = 'used';
                        $stock->save(false);
                    }
                }
            }
        }
        if(isset($_GET['get'])){
            if(!isset($_POST['type'],$_POST['title'])){
                die( Json::encode(['access'=>'false']));
            }
            $get = Stock::find()->where(['type' => $_POST['type']])
                ->andWhere(['title' => $_POST['title']])
                ->andWhere(['status' => 'good'])
                ->asArray()->all();
            if(!is_null($get)){
                $_values = [];

                foreach($get as $kk => $vv){
                    $_values[$vv['id']] = $vv['contain']." # ".$vv['representative_no'];
                }
//                print_r($_values);die();
                if(count($_values) > 0){
                    die( Json::encode([
                        'access'=>'true',
                        'data' => ['0' => '-- select --']+$_values,
                    ]));
                }else{
                    die( Json::encode([
                        'access'=>'false'
                    ]));
                }


            }else{
                die( Json::encode(['access'=>'false']));
            }
        }
        Yii::$app->on('backend/crud/form/javascript', function ($event)  {
            $script = <<< JS
                    $(function(){
                        var selectedArray = [];
                        $('.container-items').on('change','.title,.type,.source',function(){
                            var parent = $(this).closest('.form-options-body');
                            if(parent.find('.source').val() == 'stock'){
                                jQuery.ajax({
                                    'type' : 'post',
                                    'dataType' : 'json',
                                    'url' : jQuery(this).closest('form').attr('action')+"&get=true&stock_id="+parent.find('.id').val(),
                                    'data' : {'type' : parent.find('.type').val(),'title':parent.find('.title').val()}
                                }).done(function(e) {
                                    if(e.access == "true"){
                                        parent.find('.testing').remove();
                                        parent.find('.source').closest('.col-md-3').after('<div class="col-md-12 m-b-10 testing"><select class="form-control"></select></div>');
                                        console.log(e.data);
                                        $.each(e.data, function(value,key) {
                                            if($.inArray( value , selectedArray) == -1){
                                                parent.find('.testing select').append($("<option></option>").attr("value", value).text(key));
                                            }

                                        });
                                    }else if(e.access == "false"){
                                        parent.find('.testing').remove();
                                    }
                                });

                            }
                        });

                        $('.container-items').on('change','.testing select',function(){
                            var parent = $(this).closest('.form-options-body');
                            if(parent.find('.source').val() == 'stock'){
                                parent.find('.id').val($(this).val());
                                parent.find('.dynamic-id').html("#"+$(this).val());
                                selectedArray.push($(this).val());
                            }
                        });
                    });

JS;
            echo $script;
        });
    }


}