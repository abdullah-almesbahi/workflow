<?php

namespace backend\controllers;

use backend\models\Plate;
use backend\models\Plateoption;
use backend\models\Model;
use backend\models\Removeplate;
use backend\models\Stock;
use backend\widgets\BackendWidget;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

class RemoveplateController extends CrudController
{
    public static $modelClass = '\backend\models\Removeplate';
    public static $tableName = 'removeplate';

//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'actions' => ['delete','remove-all'],
//                        'roles' => ['delete plate'],
//                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['index','update','upload','remove','save-info'],
//                        'roles' => ['view plate'],
//                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['getAfTree','reorder-af-tree','af','af-update','af-delete','af-order','af-get-all-fields'],
//                        'roles' => ['developer'],
//                    ],
//                ],
//            ],
//        ];
//    }

    /**
     *
     * @param null $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionUpdate($id = null, $redirect = ['update'])
    {
        //update old_offer_id
//        $stocks = Stock::find()->where(['>' , 'plate_id' , '0' ])->andWhere(['IS','old_offer_id', 'NULL'])->all();
        $stocks = Stock::findBySql("SELECT * FROM `stock` where plate_id > 0 and old_offer_id IS NULL")->all();
        if(!is_null($stocks)){
            foreach($stocks as $stock){
                if(isset($stock->plate)){
                    $stock->old_offer_id = $stock->plate->offer_id;
                    $stock->save(false);
                }
            }
        }

        //confirm dialog when employee click on update , he has to re-enter customer id for double sure
//        Yii::$app->on('backend/crud/form/field/before/stock_ids', function ($event) {
//            $this->eventLoadStocks();
//        });
        Yii::$app->on('backend/crud/form/javascript', function ($event) {
            $this->eventJavascript();
        });
        //print label
//        $this->updateExtraUpdateHandler = [$this, 'printLabelS1'];
        return parent::actionUpdate($id, $redirect);
    }


    /**
     * Find Removeplate By plate ID
     * @param null $id
     */
    public function actionFindByPlateId($id = null)
    {

        if (!is_null($id)) {
            $stocks = Stock::find()->where(['old_offer_id' => $id])->all();
            if(!is_null($stocks)){
                foreach($stocks as $i => $stock){

                    $x[$i] = ['id' => $stock->id , 'old_offer_id' => $stock->old_offer_id];
                    $x[$i]['size'] = $stock->size->title;
                    if(isset($_GET['removeplate_id'])){
                        $removeplate = Removeplate::findOne($_GET['removeplate_id']);
                        if(in_array($stock->id , $removeplate->stock_ids)) {
                            $x[$i]['checked'] = 'true';
                        }else{
                            $x[$i]['checked'] = 'false';
                        }
                    }
                }
            }
            $out['results'] = $x;
            $out['access'] = true;
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => \common\models\User::findOne($id)->username];
        } else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
            $out['access'] = false;
        }
        echo \yii\helpers\Json::encode($out);
    }


    //-------------------------------------------------------------------
    //--- Events functions -----------------------------------------------
    //-------------------------------------------------------------------
    public function eventJavascript()
    {
        echo $this->renderPartial('_js');
    }
}