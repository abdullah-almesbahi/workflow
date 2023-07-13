<?php

namespace backend\models;

use Yii;

/**
 * The followings are the available columns in table 'tbl_audit_trail':
 * @var integer $id
 * @var string $new_value
 * @var string $old_value
 * @var string $action
 * @var string $model
 * @var string $field
 * @var string $stamp
 * @var integer $user_id
 * @var string $model_id
 */
class AuditTrail extends \sammaye\audittrail\AuditTrail
{
    public function rules()
    {
        return array_merge([
            [['color'], 'safe']
        ],parent::rules());
    }

    public function getUser()
    {
        return $this->hasOne('backend\models\Admin', ['id' => 'user_id']);
    }

}