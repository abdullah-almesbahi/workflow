<?php

namespace backend\modules\report\models;

use Yii;

class Column extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%report_column}}';
    }

}
