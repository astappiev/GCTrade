<?php
Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');

return [
    'id' => 'gctrade-console',
    'name' => 'GCTrade',
    'language'=>'ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
    'components' => [
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
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            //'useFileTransport' => true,
            'viewPath' => '@app/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.ukraine.com.ua',
                'username' => 'admin@gctrade.ru',
                'password' => 'xY200yaI',
                'port' => '25s',
                'encryption' => 'tls',
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=astappev.mysql.ukraine.com.ua;dbname=astappev_gctrade',
            'username' => 'astappev_gctrade',
            'password' => 'd3u8f939',
            'charset' => 'utf8',
            'tablePrefix' => 'tg_',
        ],
    ],
    'params' => $params,
];
