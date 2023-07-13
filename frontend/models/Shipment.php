<?php
namespace frontend\models;

use Yii;


/**
 * ActionLogSearch represents the model behind the search form about `backend\models\ActionLog`.
 */
class Shipment extends Crud
{
    public static function tableName()
    {
        return '{{%shipment}}';
    }
    public function getProducts(){
        return $this->hasMany(ComplianceProduct::className(), ['shipment_id' => 'id']);
    }

    public function rules()
    {
        $return = [
            [['notes'],'string']
        ];

        return $return;
    }

}