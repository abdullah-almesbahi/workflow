<?php

namespace frontend\models;

use backend\models\AF;
use raoul2000\workflow\validation\WorkflowValidator;
use Yii;
use yii\base\Event;

/**
 * This is the model class for table "{{%Crud}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $des
 * @property string $test
 */
class Crud extends \common\models\Crud
{

    public static $table_name;

    /**
     * @inheritdoc
     */
    public static function setTableName($table_name)
    {
        self::$table_name = $table_name;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return self::$table_name;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $af = new AF();

        $af->setParentTableName(strtolower(self::$table_name));
//        $where = [];
//        if ($role = key(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))){
//            $where = [
//                ['like', 'display', key(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))],
//            ];
//        }

        $rules = $af->getAll();
        $require = $integer = $number = $safe = $email = [];
        $other = [];
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
                        //TODO : check if workflow is enabled
                        if ($v->attributes['name'] == 'status') {
//                            $other = [['status'], WorkflowValidator::className()];
                        } else {
                            $safe[] = $v->attributes['name'];
                        }
                        break;
                }
            }

        }
        if(is_array($other) && count($other) > 0){
            $return = [
                [$require,'required'],
                [$integer,'integer'],
                [$number,'number'],
                [$email,'email'],
                [$safe,'safe'],
                $other
            ];
        }else{
            $return = [
                [$require,'required'],
                [$integer,'integer'],
                [$number,'number'],
                [$email,'email'],
                [$safe,'safe']
            ];
        }
        return $return;
    }
}
