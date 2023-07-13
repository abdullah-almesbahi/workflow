<?php
namespace backend\models;

use Yii;

/**
 * Assignpreview represents the model behind WorkFlow engine.
 */
class Assignpreview extends Crud
{
    public static function tableName()
    {
        return 'assignpreview';
    }

    public function behaviors()
    {
        if(Yii::$app->controller->id == 'assignpreview' && !Yii::$app->request->isAjax) {
            return array_merge(parent::behaviors() , [
                // Primary workflow
                'w1' =>[
                    'class' => \raoul2000\workflow\base\SimpleWorkflowBehavior::className(),
                    'statusAttribute' => 'status',
                    'defaultWorkflowId' => 'AsssignpreviewWorkflow1' // this workflow must have number 1 so we can get right definition of workflow
                ],
            ]);
        }else{
            return parent::behaviors();
        }
    }


}