<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginForm;
use backend\models\Search;
use backend\modules\shipping\models\Shipment;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\PasswordResetRequestForm;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
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
                        'actions' => ['login', 'error', 'quick-login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'search', 'auto-complete-search'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            //            'verbs' => [
            //                'class' => VerbFilter::className(),
            //                'actions' => [
            //                    'logout' => ['post'],
            //                ],
            //            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        //        $connection = Yii::$app->db2;
        //        $time = (time()-60*60*24);
        //        $command = $connection->createCommand("SELECT * FROM realestates WHERE realestate_active = '2' AND realestate_add_date >= ".$time." ");
        //        $states = $command->queryAll();
        //        $add = [];
        //        if(count($states) > 0 && is_array($states) ){
        //            foreach($states as $k => $state){
        //                //if is respentive , allowed
        //                if($state['realestate_user_id'] > 0){
        //                    $user = $connection->createCommand("SELECT * FROM users WHERE emp_mandop = '1' AND id = ".$state['realestate_user_id'])->queryOne();
        //                    if(!is_array($user) || count($user) < 1){
        //                        continue;
        //                    }
        //                    $add[$k] = $state;
        //                    $add[$k]['user'] = $user;
        //                }
        //
        //                //if is plate install enabled , then allowed
        //                if($state['realestate_cat_id'] > 0){
        //                    $cat = $connection->createCommand("SELECT GROUP_CONCAT(data_group_id) as group_ids FROM data_groups WHERE data_group_cats_id LIKE '%".$state['realestate_cat_id']."%' ")->queryOne();
        //                    $cat = $connection->createCommand("SELECT * FROM other_data WHERE data_group_id IN (".$cat['group_ids'].") ")->queryAll();
        //                }
        //
        //            }
        //        }
        //        print_r($add);die();
        //        $command = $connection->createCommand('UPDATE post SET status=1');
        //        $command->execute();
        //        $list = Yii::$app->db2->createCommand((new \yii\db\Query)->select('*')->from('realestates'))->queryAll();
        ////            ->where()

        //        print_r($list);die();
        //

        //return $this->redirect(['shipping/order/all-orders'],302);
        return $this->render('index');
    }

    public function actionLogin()
    {
        //        $admin = Admin::find()->where(['id' => 2] )->one();
        //        $admin->scenario = 'admin';
        //        $admin->setPassword('123456789');
        //        $admin->generateAuthKey();
        //        $admin->save();
        //        die('changed');


        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $prr_model = new PasswordResetRequestForm();
            $this->layout = 'login';
            return $this->render('login', [
                'model' => $model,
                'prr_model' => $prr_model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSearch()
    {
        //        if (!isset($_GET['Search']['q']) || empty($_GET['Search']['q']) ) {
        //            throw new ForbiddenHttpException(Yii::t('admin','The search field is empty , Please search again.'));
        //        }
        $model = new Search();

        if ($model->load(Yii::$app->request->get())) {
        } else {
        }
        return $this->render(
            'search',
            [
                'model' => $model,
            ]
        );
    }

    public function actionQuickSearch()
    {
        if (!isset($_GET['Search']['q']) || empty($_GET['Search']['q'])) {
            throw new ForbiddenHttpException(Yii::t('admin', 'The search field is empty , Please search again.'));
        }
        $model = new Search();
        $model->load(Yii::$app->request->get());
        return $this->render(
            'search',
            [
                'model' => $model,
            ]
        );
    }

    public function actionAutoCompleteSearch($term)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $query = Shipment::find()->orderBy('id');
        foreach (['id', 'in_tracking'] as $attribute) {
            $query->orWhere(['like', $attribute, $term]);
        }
        $products = $query->limit(5)->all();
        $result = [];
        foreach ($products as $product) {
            $result[] = [
                'value' => $this->renderPartial(
                    'auto-complete-item-template',
                    [
                        'product' => $product,
                    ]
                ),
            ];
        }
        return $result;
    }

    public function actionQuickLogin($id)
    {
        if (YII_DEBUG && YII_ENV == 'dev') {
            //            Yii::$app->user->logout();
            $admin = Admin::findOne($id);
            Yii::$app->user->login($admin,  0);
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
}
