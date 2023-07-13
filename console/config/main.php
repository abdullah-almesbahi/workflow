<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'console\controllers',
    'modules' => [
        'gii' => 'yii\gii\Module',
        'mailer' =>  [
            'class' => '\backend\modules\mailer\Mailer',
            // This is the default value, for attaching the images used into the emails.
            'attachImages' => true,
            // Also the default value, how much emails should be sent when calling yiic mailer
            'sendEmailLimit'=> 500,
        ],
        'background' => [
            'class' => 'backend\modules\backgroundtasks\BackgroundTasksModule',
            //'controllerNamespace' => 'backend\modules\backgroundtasks\commands'
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'backend\modules\mailer\components\Mailer'
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'timeout' => 2592000, // 30 days
        ],
    ],
    'params' => $params,
];
