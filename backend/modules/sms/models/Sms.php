<?php

namespace backend\modules\sms\models;

use Yii;
use backend\models\Crud;
use yii\base\UnknownClassException;
use yii\helpers\Json;

/**
 * Workflow Model.
 */
class Sms extends Crud
{

    private $sms;

    public static function tableName()
    {
        return '{{%sms}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class', 'params'], 'required'],
            [['params'], 'string'],
            [['active', 'sort'], 'integer'],
            [['name', 'class', 'logo'], 'string', 'max' => 255]
        ];
    }

    public function scenarios()
    {
        return [
            'default' => ['name', 'class', 'params', 'logo', 'active', 'sort'],
            'search' => ['id', 'name', 'class', 'active', 'sort'],
        ];
    }

    public function getSms()
    {
        if (is_null($this->sms)) {
            $className = $this->class;
            if (!class_exists($className)) {
                throw new UnknownClassException;
            }

            try {
                $params = Json::decode($this->params);
            } catch (Exception $e) {
                $params = [];
            }

            $this->sms = new $className($params);
            $this->sms->id = $this->id;
        }
        return $this->sms;
    }

}