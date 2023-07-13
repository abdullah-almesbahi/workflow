<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class PlateoptionController extends CrudController
{
    public static $modelClass = '\backend\models\Plateoption';
    public static $tableName = 'plateoption';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['delete plate'],
                    ],
                    [
                        'allow' => false,
                        'actions' => ['delete plate'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['view plate'],
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