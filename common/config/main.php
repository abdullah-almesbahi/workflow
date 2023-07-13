<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'en-US',
    'timeZone' => 'Asia/Riyadh',
    'modules' => [
        'mailer' =>  [
            'class' => '\backend\modules\mailer\Mailer',
            // This is the default value, for attaching the images used into the emails.
            'attachImages' => false,
            // Also the default value, how much emails should be sent when calling yiic mailer
            'sendEmailLimit'=> 500,
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class'=>'yii\\rbac\\DbManager',
            'cache' => 'cache',
        ],
        'session' => [
            'timeout' => 2592000, // 30 days
        ],
    ],
];
