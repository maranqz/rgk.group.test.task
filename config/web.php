<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '8S4C4I93mR71gxJmpDK0NH9gdPPbqeZr',
            'baseUrl' => '',
        ],

        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['guest'],
        ],


        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\user\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,


        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [

            ],
        ],

        'view' => [
            'theme' => [
                'pathMap' => [
                    //'@vendor\dektrium\user\views' => '@app\views\user',@vendor').'\dektrium\yii2-user\views
                    //'@vendor\dektrium\yii2-user\views' => '@app\views\user\admin',
                    '@vendor/dektrium/yii2-user/views' => '@app/views/user',
                    '\dektrium\user\views\admin' => '@app\views\user\admin'
                ],
            ],
        ],
    ],
    'defaultRoute' => 'post',
    'modules' => [
        //description User module https://github.com/dektrium/yii2-user/blob/master/docs/configuration.md
        'user' => [
            'class' => '\dektrium\user\Module',
            //RBAC role for access to special admin pages yii2-user
            'adminPermission' => 'admin',
            //If this option is set to true, module sends email that contains a confirmation link that user must click to complete registration.
            'enableConfirmation' => true,
            'mailer' => [
                'sender' => 'no-reply@myhost.com', // or ['no-reply@myhost.com' => 'Sender name']
                'welcomeSubject' => 'Welcome subject',
                'confirmationSubject' => 'Confirmation subject',
                'reconfirmationSubject' => 'Email change subject',
                'recoverySubject' => 'Recovery subject',
            ],

            'modelMap' => [
                'User' => '\app\models\user\User',
                'UserSearch' => '\app\models\user\UserSearch',
                'Profile' => '\app\models\user\Profile',
            ],
            'controllerMap' => [
                'registration' => '\app\controllers\user\RegistrationController',//::className(),
                'admin' => '\app\controllers\user\AdminController'//::className(),
            ],
            'components' => [

            ],
        ],
        'noty' => [
            'class' => 'lo\modules\noty\Module',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
