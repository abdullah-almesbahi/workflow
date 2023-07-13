<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Class Admin
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
class Admin extends \common\models\User
{

    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        if (Yii::$app->getSession()->has('admin-'.$id)) {
            return new self(Yii::$app->getSession()->get('admin-'.$id));
        } else {
            if (is_numeric($id)) {
                $model = Yii::$app->cache->get("Admin:$id");
                if ($model === false) {
                    $model = static::findOne($id);
                    if ($model !== null) {
                        Yii::$app->cache->set("Admin:$id", $model, 3600);
                    }

                }
                return $model;
            } else {
                return null;
            }
        }
    }

}
