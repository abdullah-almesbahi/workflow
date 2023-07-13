<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class StockController extends CrudController
{
    public static $modelClass = '\backend\models\Stock';
    public static $tableName = 'stock';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['delete stock'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index','update','upload','remove','save-info'],
                        'roles' => ['view stock'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['getAfTree','reorder-af-tree','af','af-update','af-delete','af-order','af-get-all-fields'],
                        'roles' => ['developer'],
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
    public function actionUpdate($id = null, $redirect = ['index'])
    {

        return parent::actionUpdate($id, $redirect);
    }
}