<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'dynagrid' =>  [
            'class' => '\kartik\dynagrid\Module',
            'dbSettings' => [
                'tableName' => 'Dynagrid',
            ],
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
    ],
    'components' => [
        'user' => [
            'class' => '\yii\web\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/login'],
        ],
        'view' => [
            'theme' => [
                'pathMap' => ['@app/views' => '@app/themes/default'],
                'baseUrl' => '@web/themes/default',
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login/<service:google_oauth|facebook|etc>' => 'default/login',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'signup' => 'site/signup',
                'search' => 'site/search',
            ],
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/../messages',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOpenId'
                ],
                'facebook' => [
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '547268812035683',
                    'clientSecret' => '478d1f1024ee3b3c90cc976eb2ee6ff5',
                ],
            ],
        ],
        'apiServiceClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                // the client's name is written in the Callback URI
                'yandexwebmaster' => [
                    'class' => 'yii\authclient\clients\YandexOAuth',
                    'clientId' => '3ba7c6d1cc474483832bbfed8050a8e0',
                    'clientSecret' => '3a3b8b551b7e4c70b05274cf62688784',
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'session' => [
            'name' => 'PHPFRONTSESSID',
            'savePath' => sys_get_temp_dir(),
        ],
    ],
    'params' => $params,
];
