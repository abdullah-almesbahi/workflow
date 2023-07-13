<?php

namespace backend\modules\report\models;

use Yii;

class Condition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%report_condition}}';
    }

}
