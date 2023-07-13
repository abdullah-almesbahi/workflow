<?php

namespace common\lib;


use Yii;

Class Formatter
{

    public static function getDate($timestamp){
        $formatter = new \yii\i18n\Formatter;
        $formatter->datetimeFormat = 'php:m-d-Y h:i:s A';
        $formatter->timeZone = Yii::$app->request->cookies->getValue('myTimeZone1' , 'Asia/Riyadh');
        return $formatter->asDatetime($timestamp);
    }

}