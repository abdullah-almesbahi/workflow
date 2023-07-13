<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'en',
    'timeZone' => 'Asia/Riyadh',
    'modules' => [
        'dynagrid' =>  [
            'class' => '\kartik\dynagrid\Module',
            'dbSettings' => [
                'tableName' => 'Dynagrid',
            ],
        ],
        'mailer' =>  [
            'class' => '\backend\modules\mailer\Mailer',
            // This is the default value, for attaching the images used into the emails.
            'attachImages' => true,
            // Also the default value, how much emails should be sent when calling yiic mailer
            'sendEmailLimit' => 500,
        ],
        'sms' =>  [
            'class' => '\backend\modules\sms\Sms',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'datecontrol' =>  [
            'class' => 'kartik\datecontrol\Module',

            // format settings for displaying each date attribute
            'displaySettings' => [
                'date' => 'd-m-Y',
                'time' => 'H:i:s A',
                'datetime' => 'd-m-Y H:i:s A',
            ],

            // format settings for saving each date attribute
            'saveSettings' => [
                'date' => 'Y-m-d',
                'time' => 'H:i:s',
                'datetime' => 'Y-m-d H:i:s',
            ],



            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,

        ],
        'background' => [
            'class' => 'backend\modules\backgroundtasks\BackgroundTasksModule',
            'layout' => '@app/views/layouts/main',
            'controllerNamespace' => 'backend\modules\backgroundtasks\controllers',
            'notifyPermissions' => ['task manage'],
            'manageRoles' => ['admin'],
        ],
        'comments' => [
            'class' => 'rmrevin\yii\module\Comments\Module',
            'userIdentityClass' => 'backend\models\Admin',
            'useRbac' => false,
        ]
    ],
    'components' => [
        'session' => [
            'name' => 'PHPBACKSESSID',
            'savePath' => sys_get_temp_dir(),
        ],
        'settings' => [
            'class' => 'backend\components\Settings'
        ],
        'workflowSource' => [
            'class' => 'raoul2000\workflow\source\file\WorkflowFileSource',
            'definitionLoader' => [
                'class' => 'backend\models\PhpClassLoader',
                'namespace'  => 'backend\models\\'
            ],
        ],
        'mailer' => [
            'class' => 'backend\modules\mailer\components\Mailer'
        ],
        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_backendUser', // unique for backend
            ]
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '214315adsg153',
            'csrfParam' => '_backendCSRF',
        ],
        'i18n' => [
            'translations' => [
                'admin' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@rootApp/messages',
                    'fileMap' => [
                        'admin' => 'admin.php',
                    ],
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
        'assetManager' => [
            'bundles' => [
                //                'yii\bootstrap\BootstrapAsset' => [
                //                    'css' => [],
                //                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'urlManagerFrontEnd' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => '/workflow/frontend/web',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
    'params' => $params,
];
