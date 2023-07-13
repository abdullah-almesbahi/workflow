<?php
namespace backend\models;

use Yii;

/**
 * Plate represents the model behind WorkFlow engine.
 */
class Removeplate extends Crud
{
    public static function tableName()
    {
        return 'removeplate';
    }

    public function init()
    {
        $this->on('beforeChangeStatusFrom{RemoveplateWorkflow/accountant}to{RemoveplateWorkflow/paid}', [$this, 'updateStockStatus']);
        parent::init();
    }

    public function behaviors()
    {
        if(Yii::$app->controller->id == 'removeplate' && !Yii::$app->request->isAjax) {
            return array_merge(parent::behaviors() , [
                // Primary workflow
                'w1' =>[
                    'class' => \raoul2000\workflow\base\SimpleWorkflowBehavior::className(),
                    'statusAttribute' => 'status',
                    'defaultWorkflowId' => 'RemoveplateWorkflow'
                ],
            ]);
        }else{
            return parent::behaviors();
        }

    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            if(isset($this->stock_ids) && is_array($this->stock_ids) && count($this->stock_ids) > 0){
                $this->stock_ids = serialize($this->stock_ids);
            }
            return true;
        }
    }
    public function afterFind()
    {
        parent::afterFind();
        if(isset($this->stock_ids) && strpos($this->stock_ids,',') !== false){
            $entry_value = unserialize($this->stock_ids);
            if(is_array($this->stock_ids) && count($entry_value) > 0){
                $this->stock_ids = $entry_value;
            }
        }
        elseif(isset($this->stock_ids)){
            $data = @unserialize($this->stock_ids);
            if ($this->stock_ids === 'b:0;' || $data !== false) {
                $this->stock_ids = $data;
            }
        }
    }

    public function updateStockStatus($event,$test = false){

        if(isset($event->sender)){
            $model = $event->sender->owner;
        }else{
            $model = $event;
        }

        if($model->new_plate_status == 'broken' || $model->new_plate_status == 'recycle'){
            Stock::updateAll(['status' => 'bad'], ['IN', 'id', $model->stock_ids]);
        }else{
            Stock::updateAll(['status' => 'good'], ['IN', 'id', $model->stock_ids]);
        }

    }






}