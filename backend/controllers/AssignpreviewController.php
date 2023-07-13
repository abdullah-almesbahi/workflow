<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class AssignpreviewController extends CrudController
{
    public static $modelClass = '\backend\models\Assignpreview';
    public static $tableName = 'assignpreview';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['delete assign'],
                    ],
                    [
                        'allow' => false,
                        'actions' => ['delete assign'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['view assign'],
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