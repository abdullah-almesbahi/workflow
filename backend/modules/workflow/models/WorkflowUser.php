<?php

namespace backend\modules\workflow\models;

use Yii;
use backend\models\Crud;

/**
 * Workflow Model.
 */
class WorkflowUser extends Crud
{

    public static function tableName()
    {
        return '{{%workflowuser}}';
    }

    public function getLevels()
    {
        return $this->hasMany( WorkflowLevel::className(), ['workflowlevel_id' => 'id']);
    }

}