<?php

namespace backend\models;

use Yii;

/**
 * Class User
 * @package backend\models
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

}
