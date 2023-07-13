<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class SizeController extends CrudController
{
//    public static $modelClass = '\backend\models\Size';
//    public static $tableName = 'size';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['delete size'],
                    ],
                    [
                        'allow' => false,
                        'actions' => ['delete size'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['view size'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'af-delete' => ['post'],
                ],
            ],
        ];
    }
}