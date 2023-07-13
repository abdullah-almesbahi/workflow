<?php

namespace backend\controllers;

use backend\models\Plate;
use backend\models\Plateoption;
use backend\models\Model;
use backend\models\Stock;
use backend\widgets\BackendWidget;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

class ZoneController extends CrudController
{
    public static $modelClass = '\backend\models\Zone';
    public static $tableName = 'zone';

    var $dynamicViewMode = false;

    // public function behaviors()
    // {
    //     return [
    //         'access' => [
    //             'class' => AccessControl::className(),
    //             'rules' => [
    //                 [
    //                     'allow' => true,
    //                     'actions' => ['delete', 'remove-all'],
    //                     'roles' => ['delete plate'],
    //                 ],
    //                 [
    //                     'allow' => true,
    //                     'actions' => ['index', 'update', 'upload', 'remove', 'save-info'],
    //                     'roles' => ['view plate'],
    //                 ],
    //                 [
    //                     'allow' => true,
    //                     'actions' => ['getAfTree', 'reorder-af-tree', 'af', 'af-update', 'af-delete', 'af-order', 'af-get-all-fields'],
    //                     'roles' => ['developer'],
    //                 ],
    //             ],
    //         ],
    //     ];
    // }


}
