<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\base\Security;

/**
 * Login form
 */
class LoginForm extends model
{
    public $username;
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getAdmin();
            if (!$user || !$user->validatePassword($this->password)) {
//                print_r($user->validatePassword($this->password));die();
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
        return true;
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {

        if ($this->validate()) {
            return Yii::$app->user->login($this->getAdmin(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds admin by [[username]]
     *
     * @return Admin|null
     */
    public function getAdmin()
    {
        if ($this->_user === false) {
            $this->_user = Admin::findByUsername($this->username);
        }
        return $this->_user;
    }
}
