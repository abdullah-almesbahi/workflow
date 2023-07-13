<?php
namespace frontend\models;

use frontend\models\Crud;
use Yii;


/**
 * ActionLogSearch represents the model behind the search form about `backend\models\ActionLog`.
 */
class ComplianceProduct extends Crud
{
    public static function tableName()
    {
        return '{{%complianceproduct}}';
    }
}