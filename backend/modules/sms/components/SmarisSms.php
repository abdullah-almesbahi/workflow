<?php
namespace backend\modules\sms\components;


class SmarisSms extends AbstractSms {

    private $wsdl_link = "http://ssms.ws/";
    public $username;
    public $password;
    public $from;
    public $to;
    public $msg;


    function SendSMS() {

        $username = urlencode($this->username);
        $password = $this->password;
        $sender = $this->from;
        $message = urlencode($this->msg);
        $final_url = $this->wsdl_link . "sendsms.php?user=" . $username . "&password=" . $password . "&numbers=" . implode($this->to, ",") . "&sender=" .urlencode($sender) . "&message=" . $message . "&unicode=E&return=xml";
        $response = file_get_contents($final_url);
        return $response ;
    }


    function GetCredit() {

        $username = urlencode($this->username);
        $password = $this->password;

        return file_get_contents($this->wsdl_link . "sendsms.php?user=" . $username . "&password=" . $password .'&action=get' );

    }

}