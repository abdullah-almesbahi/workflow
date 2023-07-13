<?php
namespace backend\models;

use Yii;

/**
 * Stock represents the model behind WorkFlow engine.
 */
class Stock extends Crud
{
    public static $table_name = 'stock';

//    public function __construct($config)
//    {
//        self::$table = 'stock';
//        parent::__construct($config);
//    }

    public static function tableName()
    {
        return 'stock';
    }


    public static function getDynamicPrimaryId(){
        return 'plate_id';
    }

    public function rules()
    {

        $af = (new AF(strtolower(self::$table_name)));
        $where = [];
        //only display specific fields if workflow is enabled
        $workflow = $workflow2 = false;
        if(true === $af->isWorkflowEnabled()){
            $update_ids = $af->getWorkflowFieldsIds();
            $view_ids = $af->getWorkflowFieldsIds('wf_field_view');
            $final_ids = array_diff($update_ids,$view_ids);
            $where = [
                ['in', 'id', $final_ids],
            ];
            $workflow = true;
        }
        if(true === $af->isWorkflow2Enabled()){
            $workflow2 = true;
        }

        $rules = $af->getAll($where);
        $require = $integer = $number = $safe = $email = [];
        if(count($rules) > 0){
            foreach($rules as $v) {
                switch ($v->attributes['validate_func']) {
                    case 'require':
                        if (Yii::$app->hasEventHandlers('backend/model/crud/rule/require/' . $v->attributes['name'])) {
                            Yii::$app->trigger('backend/model/crud/rule/require/' . $v->attributes['name'], new Event(['sender' => ['rule' => $v]]));
                        } else {
                            $require[] = $v->attributes['name'];
                        }
                        break;
                    case 'integer':
                        $integer[] = $v->attributes['name'];
                        break;
                    case 'number':
                        $number[] = $v->attributes['name'];
                        break;
                    case 'email':
                        $email[] = $v->attributes['name'];
                        break;
                    default:
//                        if (true === $workflow && $v->attributes['name'] == 'status') {
//                            $other = [
//                                [['status'],WorkflowValidator::className()],
//                            ];
//                        }elseif(true === $workflow2 && $v->attributes['name'] == 'status_ex'){
//                            $other2 = [
//                                [['status_ex'],RelatedWorkflowValidator::className()]
//                            ];
//                        } else {
                        $safe[] = $v->attributes['name'];
//                        }
                        break;
                }
            }

        }
        $return = [
            [$require,'required'],
            [$integer,'integer'],
            [$number,'number'],
            [$email,'email'],
            [$safe,'safe']
        ];

        if(isset($other) && count($other) > 0 && is_array($other)){
            $return = array_merge($return,$other);
        }
        if(isset($other2) && count($other2) > 0 && is_array($other2)){
            $return = array_merge($return,$other2);
        }

        return $return;
    }

    public function getPlate()
    {
        return $this->hasOne( Plate::className(), ['id' => 'plate_id']);
    }

    public function getSize()
    {
        return $this->hasOne( Size::className(), ['id' => 'title']);
    }

}