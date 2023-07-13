<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $address_1;
    public $address_2;
    public $country;
    public $state;
    public $city;
    public $zip;
    public $mobile;
    public $phone;
    public $lang;
    public $plan;
    public $coupon;
    public $method;

    private $user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['first_name','last_name'], 'string'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
//    public function signup()
//    {
//        if ($this->validate()) {
//            $user = new User();
//            $user->username = $this->username;
//            $user->email = $this->email;
//            $user->setPassword($this->password);
//            $user->generateAuthKey();
//            if ($user->save()) {
//                return $user;
//            }
//        }
//
//        return null;
//    }

    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }
        $user = $this->getUser();
        if ($user !== null) {
            $this->addError('username', 'Choose other name');
            return false;
        }
        $user = new User(['scenario' => 'signup']);
        $user->setAttributes(
            [
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
            ]
        );
        return $user->save() && Yii::$app->user->login($user, 0);
    }

    public function signupService($serviceType, $serviceId)
    {
        if (!$this->validate()) {
            return false;
        }
        $user = $this->getUser();
        if ($user !== null) {
            $this->addError('username', 'Choose other name');
            return false;
        }
        $user = new User(['scenario' => 'signup']);
        $user->setAttributes(
            [
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
            ]
        );
        if (!$user->save()) {
            return false;
        }
        $userService = new UserService;
        $userService->setAttributes(
            [
                'user_id' => $user->id,
                'service_type' => $serviceType,
                'service_id' => $serviceId,
            ]
        );
        $userService->save();
        Yii::$app->user->login($user, 0);
        return true;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    private function getUser()
    {
        if ($this->user === false) {
            $this->user = User::findByUsername($this->username);
        }
        return $this->user;
    }
}
