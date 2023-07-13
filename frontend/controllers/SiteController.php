<?php
namespace frontend\controllers;

use backend\models\AF;
use common\models\User;
use frontend\models\LoginForm;
use frontend\modules\shipping\models\ComplianceProduct;
use frontend\models\Country;
use frontend\modules\shipping\models\Order;
use frontend\modules\shipping\models\Plan;
use frontend\modules\shipping\models\Shipment;
use frontend\modules\shipping\models\Transaction;
use kartik\grid\GridView;
use Yii;
use common\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
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
                //'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => [ 'login','request-password-reset'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        //'actions' => ['logout' ,'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $pagesize = 12;

        //Shipments
        $query = Shipment::find()->where(['customer_id' => Yii::$app->user->id])->andWhere(['status'=>['ShipmentWorkflow1/received' , 'ShipmentWorkflow1/reviewName','ShipmentWorkflow1/ready','ShipmentWorkflow1/additionalReview']])->orderBy(['create_time' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider(['query' => $query, 'pagination' => ['pagesize' => $pagesize,]]);

        //Order
        $query = Order::find()->where(['customer_id' => Yii::$app->user->id])->orderBy(['create_time' => SORT_DESC]);
        $order_dataProvider = new ActiveDataProvider(['query' => $query, 'pagination' => ['pagesize' => 5,]]);

        //Transaction
        $query = Transaction::find()->where(['customer_id' => Yii::$app->user->id])->orderBy(['create_time' => SORT_DESC]);
        $trans_dataProvider = new ActiveDataProvider(['query' => $query, 'pagination' => ['pagesize' => 5,]]);



        return $this->render('index' , [
            'dataProvider' => $dataProvider,
            'order_dataProvider' => $order_dataProvider,
            'trans_dataProvider' => $trans_dataProvider,
        ]);
    }


    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Thank you for contacting us. We will respond to you as soon as possible.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'There was an error sending email.'));
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                return $this->goHome();
//                if (Yii::$app->getUser()->login($user)) {
//                    return $this->goHome();
//                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Sorry, we are unable to reset password for email provided.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'New password was saved.'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionCustomerCare()
    {
        return $this->render('customercare', [
        ]);
    }

}
