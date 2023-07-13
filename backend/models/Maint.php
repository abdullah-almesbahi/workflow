<?php
namespace backend\models;

use Yii;

/**
 * Plate represents the model behind WorkFlow engine.
 */
class Maint extends Crud
{
    public static function tableName()
    {
        return 'maint';
    }

    public function behaviors()
    {
        if(Yii::$app->controller->id == 'maint' && !Yii::$app->request->isAjax) {
            return array_merge(parent::behaviors() , [
                // Primary workflow
                'w1' =>[
                    'class' => \raoul2000\workflow\base\SimpleWorkflowBehavior::className(),
                    'statusAttribute' => 'status',
                    'defaultWorkflowId' => 'MaintWorkflow'
                ],
            ]);
        }else{
            return parent::behaviors();
        }

    }





}