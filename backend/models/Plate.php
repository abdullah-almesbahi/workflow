<?php
namespace backend\models;

use Yii;

/**
 * Plate represents the model behind WorkFlow engine.
 */
class Plate extends Crud
{
    public static function tableName()
    {
        return 'plate';
    }

    public function init()
    {
        $this->on(
            'afterChangeStatusFrom{PlateWorkflow/secretary}to{PlateWorkflow/designer}',
            [$this, 'updateStock']
        );
        parent::init();
    }

    public function behaviors()
    {
        if(Yii::$app->controller->id == 'plate' && !Yii::$app->request->isAjax) {
            return array_merge(parent::behaviors() , [
                // Primary workflow
                'w1' =>[
                    'class' => \raoul2000\workflow\base\SimpleWorkflowBehavior::className(),
                    'statusAttribute' => 'status',
                    'defaultWorkflowId' => 'PlateWorkflow'
                ],
            ]);
        }else{
            return parent::behaviors();
        }

    }

    public function getOptions(){
        return $this->hasMany( Plateoption::className(), ['plate_id' => 'id']);
    }

    public function updateStock(){
        $post = $post = \Yii::$app->request->post();
        //if stock is choosen
        if(isset($post['Plate']['plate_source']) && $post['Plate']['plate_source'] == 'stock' && isset($post['Plate']['plate_status']) ){
            $stock = Stock::findOne($post['Plate']['plate_status']);
            $stock->qty = ($stock->qty-1);
            $stock->save(false);
        }
    }

    /**
     * Creates and populates a set of models.
     *
     * @param string $modelClass
     * @param array $multipleModels
     * @return array
     */
    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model    = new $modelClass;
        $formName = $model->formName();
        $post     = Yii::$app->request->post($formName);
        $models   = [];

        if (! empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }


}