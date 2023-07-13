<?php
namespace backend\models;

use Yii;

/**
 * Stock represents the model behind WorkFlow engine.
 */
class Size extends Crud
{
    public static $table_name = 'size';

//    public function __construct($config)
//    {
//        self::$table = 'stock';
//        parent::__construct($config);
//    }

    public static function tableName()
    {
        return 'size';
    }

}