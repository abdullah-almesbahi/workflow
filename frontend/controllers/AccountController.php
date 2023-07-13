<?php
namespace frontend\controllers;

use backend\models\AF;
use frontend\models\Address;
use Yii;
use common\models\LoginForm;
use common\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\base\Security;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use yii\web\Response;

/**
 * Account controller
 */
class AccountController extends CrudController
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
        /** @var User|HasProperties $model */
        $model = User::findOne(Yii::$app->user->id);
        $model->scenario = 'updateProfile';
        if ($model->load(Yii::$app->request->post()) && $model->validate() ) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Your profile has been updated'));
                $this->refresh();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Internal error'));
            }
        }
        $af = new AF('user');
        return $this->render('index',[
            'fields' => $af->getAll("display LIKE '%profile%'"),
            'services' => ArrayHelper::map($model->services, 'id', 'service_type'),
            'model' => $model,
        ]);
    }

    public function actionChangePassword()
    {
        $model = User::findOne(Yii::$app->user->id);
        if (is_null($model)) {
            throw new NotFoundHttpException;
        }
        $model->scenario = 'changePassword';
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $formIsValid = $model->validate();
            $passwordIsValid = $model->validatePassword($model->password);
            if (!$passwordIsValid) {
                $model->addError('password', Yii::t('app', 'Wrong password'));
            }
            if ($formIsValid && $passwordIsValid) {
                $security = new Security;
                $model->password_hash = $security->generatePasswordHash($model->newPassword);
                if ($model->save(true, ['password_hash'])) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Password has been changed'));
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Internal error'));
                }
            }
        }
        return $this->render('change-password', ['model' => $model]);
    }

    public function actionUpgrade(){
        $user = \frontend\models\User::findOne(Yii::$app->user->id);
        return $this->render('upgrade', ['plan' => $user->plan]);
    }

    public function actionBilling(){
        $model = \frontend\models\User::findOne(Yii::$app->user->id);
        $model->scenario = 'checkAutopay';
        if ($model->load(Yii::$app->request->post()) &&$model->validate() ) {
            if ($model->save(false)) {
                $response = ['access' => true , 'message' => Yii::t('app', 'Auto Pay has been updated sucessfully.')];
            } else {
                $response = ['access' => false , 'message' => Yii::t('app', 'Internal error')];
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }
        return $this->render('billing', ['user' => $model]);
    }
    public function actionWireTransfer(){
        $model = \frontend\models\User::findOne(Yii::$app->user->id);
        $model->scenario = 'checkWireTransfer';
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post()) && $model->validate() ) {
            if ($model->save(false)) {
                $response = ['access' => true , 'message' => Yii::t('app', 'Wire Transfer has been updated sucessfully.')];
            } else {
                $response = ['access' => false , 'message' => Yii::t('app', 'Internal error')];
            }

            return $response;
        }
        return ['access' => false , 'message' =>  Yii::t('app', 'Internal error')];
    }
}
