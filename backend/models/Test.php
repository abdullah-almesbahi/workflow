<?php
namespace backend\models;

use Yii;

/**
 * Test represents the model behind WorkFlow engine.
 */
class Test extends Crud
{
    public static function tableName()
    {
        return 'test';
    }

    public function behaviors()
    {
        if(Yii::$app->controller->id == 'test' && !Yii::$app->request->isAjax) {
            return array_merge(parent::behaviors() , [
                // Primary workflow
                'w1' =>[
                    'class' => \raoul2000\workflow\base\SimpleWorkflowBehavior::className(),
                    'statusAttribute' => 'status',
                    'defaultWorkflowId' => 'TestWorkflow'
                ],
            ]);
        }else{
            return parent::behaviors();
        }

    }


}