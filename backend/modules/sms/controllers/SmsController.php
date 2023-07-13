<?php
namespace backend\modules\sms\controllers;


use Yii;
use backend\controllers\CrudController;
use backend\models\PaymentType;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


/**
 * Site controller
 */
class SmsController extends CrudController
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

}