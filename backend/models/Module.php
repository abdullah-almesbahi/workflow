<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ActionLog;

/**
 * ActionLogSearch represents the model behind the search form about `backend\models\ActionLog`.
 */
class Module extends Crud
{
    var $modelClass = '\backend\models\Module';

    public static function tableName()
    {
        return '{{%module}}';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if(!$this->settings || !is_array($this->settings)){
                $this->settings = self::getDefaultSettings($this->name);
            }

            $this->settings = json_encode($this->settings);

            return true;
        } else {
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->settings = $this->settings !== '' ? json_decode($this->settings, true) : self::getDefaultSettings($this->name , $this);
    }
//
    public static function findAllActive()
    {
        return Data::cache(self::CACHE_KEY, 3600, function(){
            $result = [];
            try {
                foreach (self::find()->where(['status' => self::STATUS_ON])->sort()->all() as $module) {
                    $module->trigger(self::EVENT_AFTER_FIND);
                    $result[$module->name] = (object)$module->attributes;
                }
            }catch(\yii\db\Exception $e){}

            return $result;
        });
    }
    public static function findOneByName($name)
    {
        return self::find()->where(['status' => '1' , 'name' => $name])->One();
    }
//
//
//    public function checkExists($attribute)
//    {
//        if(!class_exists($this->$attribute)){
//            $this->addError($attribute, Yii::t('easyii', 'Class does not exist'));
//        }
//    }
//
    static function getDefaultSettings($moduleName , $obj = [])
    {
        if(sizeof($obj) > 0){
            $name = $obj->class.'\\'.ucfirst($obj->name);
            $default_settings = $name::$default_settings;
            return $default_settings;
        } else {
            return [];
        }
    }


}