<?php

namespace backend\controllers;

use Yii;

class TestController extends CrudController
{
    public static $modelClass = '\backend\models\Test';
    public static $tableName = 'test';
}