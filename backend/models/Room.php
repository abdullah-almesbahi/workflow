<?php

namespace backend\models;

use Yii;

/**
 * Plate represents the model behind WorkFlow engine.
 */
class Room extends Crud
{
    public static function tableName()
    {
        return 'room';
    }

    public function behaviors()
    {
        // if (Yii::$app->controller->id == 'zone' && !Yii::$app->request->isAjax) {
        //     return array_merge(parent::behaviors(), [
        //         // Primary workflow
        //         'w1' => [
        //             'class' => \raoul2000\workflow\base\SimpleWorkflowBehavior::className(),
        //             'statusAttribute' => 'status',
        //             'defaultWorkflowId' => 'TransportRequestWorkflow'
        //         ],
        //     ]);
        // } else {
        return parent::behaviors();
        // }
    }
}
