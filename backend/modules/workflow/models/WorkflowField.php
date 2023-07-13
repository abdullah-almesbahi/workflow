<?php

namespace backend\modules\workflow\models;

use Yii;
use backend\models\Crud;

/**
 * Workflow Model.
 */
class WorkflowField extends Crud
{

    public static function tableName()
    {
        return '{{%af}}';
    }

    public function getLevels()
    {
        return $this->hasMany( WorkflowLevel::className(), ['workflowlevel_id' => 'id'])
            ->viaTable('wflevel_wffield', ['user_id' => 'id']);
    }

}