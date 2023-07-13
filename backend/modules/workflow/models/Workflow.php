<?php

namespace backend\modules\workflow\models;

use Yii;
use backend\models\Crud;

/**
 * Workflow Model.
 */
class Workflow extends Crud
{
    public static function tableName()
    {
        return '{{%workflow}}';
    }

    public function getLevels()
    {
        return $this->hasMany( WorkflowLevel::className(), ['workflow_id' => 'id']);
    }

}