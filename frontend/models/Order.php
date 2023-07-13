<?php
namespace frontend\models;

use frontend\models\Crud;
use Yii;


/**
 * ActionLogSearch represents the model behind the search form about `backend\models\ActionLog`.
 */
class Order extends Crud
{
    public static function tableName()
    {
        return '{{%order}}';
    }

    public function getShipments(){
        return $this->hasMany(Shipment::className(), ['order_id' => 'id']);
    }

    public function getProducts(){
        die("eee");
        return $this->hasMany(Shipment::className(), ['order_id' => 'id']);
    }

    public function getPackages(){
        return $this->hasMany(Package::className(), ['order_id' => 'id']);
    }


    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }
}