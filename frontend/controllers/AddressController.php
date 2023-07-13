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

/**
 * Address controller
 */
class AddressController extends CrudController
{

    public function actionIndex(){
        $this->searchHandler = [ $this , 'getByCustomerID'];
        Yii::$app->on('frontend/crud/index/after/title', function ($event) {
            echo $this->renderPartial('@app/themes/default/account/tabs');
        });
        return parent::actionIndex();
    }

    public function getByCustomerID($query){
        return $query->where(['customer_id' => Yii::$app->user->id]);
    }

    public function actionUpdate($id = null ,$redirect = ['index'])
    {
        $this->beforeSaveUpdateHandler = [$this , 'setBelongTo']; // Set customer id to the address
        return parent::actionUpdate($id,$redirect);
    }
    public function setBelongTo($model){
        $model->customer_id = Yii::$app->user->id;
    }

}
