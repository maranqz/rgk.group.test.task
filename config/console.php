<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [

        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['admin', 'manager', 'user', 'guest'],
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
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
        ],
    ],
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
