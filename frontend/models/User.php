<?php

namespace frontend\models;

use frontend\properties\AbstractModel;
use frontend\properties\HasProperties;
use Yii;
use yii\base\NotSupportedException;
use yii\base\Security;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package app\models
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 * @property string $first_name
 * @property string $last_name
 * @property UserService[] $services
 * @property AbstractModel $abstractModel
 */
class User extends \common\models\User
{
    public function rules()
    {
        return array_merge(parent::rules(),[
            ['enable_autopay', 'integer', 'on' => ['checkAutopay']],
            [['bank_from','bank_to','bank_acct','bank_name'], 'required', 'on' => ['checkWireTransfer']],
        ]);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(),[
            'checkAutopay' => ['enable_autopay'],
            'checkWireTransfer' => ['bank_from','bank_to','bank_acct','bank_name'],
        ]);
    }

    public function getPlan(){
        return $this->hasOne(Plan::className(), ['id' => 'plan_id']);
    }

}
