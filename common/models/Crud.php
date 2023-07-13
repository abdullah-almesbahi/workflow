<?php

namespace common\models;

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
class Crud extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    public function getfields()
    {
        $query = \backend\models\AF::find()->where(['table' => $this->getRawTableName(self::tableName())]);
        return $query;
    }

    /**
     * Returns the actual name of a given table name.
     * This method will strip off curly brackets from the given table name
     * and replace the percentage character '%' with [[Connection::tablePrefix]].
     * @param string $name the table name to be converted
     * @return string the real name of the given table name
     */
    public function getRawTableName($name)
    {
        if (strpos($name, '{{') !== false) {
            $name = preg_replace('/\\{\\{(.*?)\\}\\}/', '\1', $name);

            return str_replace('%', $this->db->tablePrefix, $name);
        } else {
            return $name;
        }
    }

}
