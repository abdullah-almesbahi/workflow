<?php

namespace backend\modules\workflow\models;

use Yii;
use backend\models\Crud;

/**
 * Workflow Model.
 */
class WorkflowLevel extends Crud
{

    public static function tableName()
    {
        return '{{%workflowlevel}}';
    }

    /**
     * Get Parent Workflow
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne( Workflow::className(), ['id' => 'workflow_id']);
    }

    /**
     * Get Users & groups in current levels
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany( WorkflowUser::className(), ['workflowlevel_id' => 'id']);
    }

    public function getFields()
    {
        return $this->hasMany( WorkflowField::className(), ['workflowlevel_id' => 'id'])
        ->viaTable('wflevel_wffield', ['user_id' => 'id']);
    }

}