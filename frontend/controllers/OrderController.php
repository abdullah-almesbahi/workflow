<?php
namespace frontend\controllers;

use backend\models\AF;
use frontend\models\Order;
use frontend\models\Plan;
use frontend\models\Shipment;
use frontend\models\Transaction;
use frontend\models\User;
use kartik\grid\GridView;
use Yii;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\ServerErrorHttpException;

/**
 * Account controller
 */
class OrderController extends CrudController
{

    public  static $modelClass = '\frontend\models\Order';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $query = Order::find();
        $needToSearch = true;
        $query->AndWhere(['customer_id' => Yii::$app->user->id]);

        if ($needToSearch) {
            $orders = $query->all();
        } else {
            $orders = [];
        }
        $shipping_soon = [];
        $processing_now = [];
        $not_process_yet = [];
        foreach ($orders as $order) {
            switch ($order->status) {
                case 'not_process_yet':
                    $not_process_yet[] = $order;
                    break;
                case 'processing_now':
                    $processing_now[] = $order;
                    break;
                case 'shipping_soon':
                    $shipping_soon[] = $order;
            }
        }
        unset($orders);
        return $this->render(
            'index',
            [
                'processing_now' => $processing_now,
                'shipping_soon' => $shipping_soon,
                'not_process_yet' => $not_process_yet,
            ]
        );
    }

    /**
     *      * Updates an existing Crud model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param null $id
     * @param array $redirect
     * @return string
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionUpdate($id = null ,$redirect = ['index'] )
    {


        $model = null;
        if (null === $id || 0 === $id) {
            //TODO : Select without the need to enter table name
            $model = new Order();
            //$model = $model->setTableName(strtolower($this->getTableName()));
            $model->loadDefaultValues();
        } else {
            $model = $this->findModel($id);
        }

        if (null === $model) {
            throw new ServerErrorHttpException;
        }

        $post = \Yii::$app->request->post();

        if(count($post) > 0){
            $plan = User::findOne(Yii::$app->user->id)->plan;

            //if declared price for all products under all shipments are above 100$ , We Sum them and get 2% of the total declared price
            if(isset($post['Order']['insurance']) && '1' == $post['Order']['insurance']){
                $d_price = 0;
                $shipments = Shipment::find()->where(['in' , 'id' , explode(',',$post['shipment_ids'])])->all();
                foreach($shipments as $shipment){
                    foreach($shipment->products as $product){
                        $d_price = ($product->price+$d_price);
                    }
                }
                $post['Order']['insurance'] = (($plan->insurance/100)*$d_price);
            }

            if(isset($post['Order']['is_fragile_sticker']) && '1' == $post['Order']['is_fragile_sticker']){
                $post['Order']['sticker_fee'] = $plan->sticker_fee;
            }
            if(isset($post['Order']['is_extra_packing']) && '1' == $post['Order']['is_extra_packing']){
                $post['Order']['extrapack_fee'] =  $plan->extrapack_fee;
            }
            if(isset($post['Order']['other_fee']) && '1' == $post['Order']['other_fee']){
                $post['Order']['other_fee'] =  $plan->urgent_fee;
            }
            //TODO: any dangerous product will only ship by DHL
            if(isset($post['Order']['dgr_fee']) && '1' == $post['Order']['dgr_fee']){
                $post['Order']['dgr_fee'] =  $plan->dgr_fee;
            }
            if(isset($post['ship_to']) && '0' != $post['ship_to']){
                $post['Order']['address_fee'] =  $plan->address_fee;
            }
            $model->customer_id = Yii::$app->user->id;
            $model->status = 'pending';
        }

        if ($model->load($post) && $model->validate()) {

            $save_result = $model->save(false);

            if ($save_result) {
                //we need change status to done on shipments plus save order id

                $shipments = Shipment::find()->where(['in' , 'id' , explode(',',$post['shipment_ids'])])->all();
                foreach($shipments as $shipment){
                    $shipment->order_id =  $model->primaryKey;
                    $shipment->status =  'done';
                    $shipment->save(false);
                }
                Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));
                return $this->redirect(['confirm','id' =>  $model->primaryKey]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Cannot save data'));
            }
        }else {

            // validation failed: $errors is an array containing error messages
            $errors = $model->errors;
            if (is_array($errors) && count($errors) > 0) {
                foreach($errors as $key => $message) {
                    Yii::$app->session->addFlash('error', implode(', ' ,$message));
                }
            }

            return $this->render('@app/themes/default/crud/update', [
                'fields' => $this->af->getAll(["display LIKE '%all_pages%'"]),
                'model' => $model,
            ]);
        }

    }

    public function actionHistory()
    {
        $query = Order::find();
        $needToSearch = true;
        $query->AndWhere(['customer_id' => Yii::$app->user->id]);
        $query->andWhere(['!=','status','pending']);

        if ($needToSearch) {
            $orders = $query->all();
        } else {
            $orders = [];
        }

        return $this->render(
            'history',
            [
                'orders' => $orders,
            ]
        );
    }
    public function actionPay($id = null)
    {
        $query = Order::find();
        $query->AndWhere(['customer_id' => Yii::$app->user->id]);
        $query->andWhere(['status' => 'waiting_payment']);
        $orders = $query->one();
        if(isset($_POST['method']) && isset($_POST['id']) ) {
            $transaction  = new Transaction();
            //TODO : fix this without insert database
            $transaction->setTableName('transaction');
            $transaction->customer_id = Yii::$app->user->id;
            $transaction->type = '+';
            $transaction->method = 'Bank';
            $transaction->amount = $orders->amount;
            $transaction->order_id = $orders->id;
            $transaction->status = '0';
            $transaction->b_from = $orders->user->bank_from;
            $transaction->b_to = $orders->user->bank_to;
            $transaction->b_name = $orders->user->bank_name;
            $transaction->b_acct = $orders->user->bank_acct;
            $transaction->b_amount = ceil(3.75*$orders->amount);
            $transaction->w_date = $_POST['date'];
            $transaction->direct = '1';

            if ($transaction->insert()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Your Wire Transfer information has been sent to our accounting department. Please allow 24-48 hours to verify and confirm your transaction.'));
                return $this->redirect(\yii\helpers\Url::to(['order/history']));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Internal error'));
            }

        }


        if(null === $orders){
            die('error id');
        }
        return $this->render(
            'pay',
            [
                'order' => $orders,
            ]
        );
    }

    /**
     * Displays a single Crud model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $order = $this->findModel($id);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = \Yii::$app->request->post();
            $model =  Shipment::findOne($post['id']);
            if ($model->load($post) && $model->save()) {
                $res = array(
                    'body'    => date('Y-m-d H:i:s'),
                    'success' => true,
                );
                return $res;
            }
            $res = array(
                'body'    => date('Y-m-d H:i:s'),
                'success' => false,
                'error' => $model->errors
            );
            return $res;

        }

        //display shipments for order
        $af_columns =  [
            [
                'class'=>'kartik\grid\ExpandRowColumn',
                'width'=>'50px',
                'value'=>function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                'detail'=>function ($model, $key, $index, $column) {
                    return Yii::$app->controller->renderPartial('@app/themes/default/site/shipmentDetails', ['model'=>$model]);
                },
                'headerOptions'=>['class'=>'kartik-sheet-style']
            ],
        ];
        $all = (new AF('shipment'))->getAll(["display LIKE '%all_pages%'"]);
        if(count($all) > 0 && is_array($all) ) {
            foreach ($all as $k => $v) {
                $af_columns[] = array(
                    'attribute' => $v->attributes['name'],
                );
            }
        }

        //display package used for order
        $all = (new AF('package'))->getAll(["display LIKE '%all_pages%'"]);
        $packageUsedAf_columns = [];
        if(count($all) > 0 && is_array($all) ) {
            foreach ($all as $k => $v) {
                $packageUsedAf_columns[] = array(
                    'attribute' => $v->attributes['name'],
                );
            }
        }



        return $this->render('view', [
            'model' => $order,
            'dataProvider' => new ActiveDataProvider(['query' => $order->getShipments()]),
            'af_columns' => $af_columns,
            'packageUsedDataProvider' => new ActiveDataProvider(['query' => $order->getPackages()]),
            'packageUsedAf_columns' => $packageUsedAf_columns,
        ]);
    }

    /**
     * Displays a single Crud model.
     * @param integer $id
     * @return mixed
     */
    public function actionConfirm($id)
    {
        $order = $this->findModel($id);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = \Yii::$app->request->post();
            $model =  Shipment::findOne($post['id']);
            if ($model->load($post) && $model->save()) {
                $res = array(
                    'body'    => date('Y-m-d H:i:s'),
                    'success' => true,
                );
                return $res;
            }
            $res = array(
                'body'    => date('Y-m-d H:i:s'),
                'success' => false,
                'error' => $model->errors
            );
            return $res;

        }
        $all = (new AF('shipment'))->getAll(["display LIKE '%all_pages%'"]);
        if(count($all) > 0 && is_array($all) ) {
            foreach ($all as $k => $v) {
                $af_columns[] = array(
                    'attribute' => $v->attributes['name'],
                );
            }
        }

        //display package used for order
        $all = (new AF('package'))->getAll(["display LIKE '%all_pages%'"]);
        $packageUsedAf_columns = [];
        if(count($all) > 0 && is_array($all) ) {
            foreach ($all as $k => $v) {
                $packageUsedAf_columns[] = array(
                    'attribute' => $v->attributes['name'],
                );
            }
        }



        return $this->render('confirm', [
            'model' => $order,
            'dataProvider' => new ActiveDataProvider(['query' => $order->getShipments()]),
            'af_columns' => $af_columns,
        ]);
    }
    public function actionConfirmed($id)
    {
        $order = Order::findOne($_GET['id']);
        $order->status = 'not_process_yet';
        $order->save(false);
        return $this->redirect(['index']);
    }
    public function actionCancelled($id)
    {
        $order = Order::findOne($_GET['id']);
        $order->status = 'cancelled';
        $order->save(false);
        $shipments = Shipment::find()->where(['order_id'=>$order->id])->all();
        foreach($shipments as $shipment){
            $shipment->status =  'registered';
            $shipment->save(false);
        }
        return $this->redirect(['site/index']);

    }


}
