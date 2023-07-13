<?php
namespace backend\modules\sms\components;

use Yii;
use yii\base\Widget;

abstract class AbstractSms extends Widget
{

    /**
     * Webservice username
     *
     * @var string
     */
    public $username;


    /**
     * Webservice password
     *
     * @var string
     */
    public $password;


    /**
     * SMS send from number
     *
     * @var string
     */
    public $from;


    /**
     * Send SMS to number
     *
     * @var string
     */
    public $to;


    /**
     * SMS text
     *
     * @var string
     */
    public $msg;

    public function __construct($params)
    {
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    abstract public function SendSMS();

    abstract public function GetCredit();



}