<?php
use yii\helpers\ArrayHelper;
//$params = require(__DIR__ . '/params.php');
$params = ArrayHelper::merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'smart-console',
    'name' => 'Домовенок',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'admin' => [ //модуль для работы с админкой
            'class' => 'app\modules\admin\Module',
            'layout' => 'admin',
        ],
        'user' => [ //модуль для работы с пользователями
            'class' => 'app\modules\user\Module',
        ],
        'main' => [ //главный модуль
            'class' => 'app\modules\main\Module',
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail/',
            'htmlLayout' => 'layouts/html',
            'textLayout' => 'layouts/text',
            'messageConfig' => [
                'charset' => 'UTF-8',
            ],
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
