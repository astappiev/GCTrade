<?php
$params = require(__DIR__ . '/params.php');
$rules = require(__DIR__ . '/rules.php');

return [
    'id' => 'gctrade',
    'name' => 'GCTrade',
    'language'=>'ru',
    'charset' => 'utf-8',
    'timeZone' => 'Europe/Kiev',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
    'components' => [
        'image' => [
            'class' => 'yii\image\ImageDriver',
            'driver' => 'GD',  //GD or Imagick
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOpenId'
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '659841520740702',
                    'clientSecret' => 'aaf81a39412ee8202d53de4bf01c72af',
                ],
            ],
        ],
        'request' => [
            'baseUrl' => '',
        ],
        'assetManager' => [
            'linkAssets' => false,
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['user/login'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            //'suffix' => '.html',
            'rules' => $rules,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            //'useFileTransport' => true,
            'viewPath' => '@app/mails',
            'messageConfig' => [
                'from' => 'admin@gctrade.ru'
            ],
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.ukraine.com.ua',
                'username' => 'admin@gctrade.ru',
                'password' => 'xY200yaI',
                'port' => '25s',
                'encryption' => 'tls',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'file' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['trace', 'info', 'error', 'warning'],
                    'categories' => ['yii\*'],
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=astappev.mysql.ukraine.com.ua;dbname=astappev_gctrade',
            'username' => 'astappev_gctrade',
            'password' => 'd3u8f939',
            'charset' => 'utf8',
            'tablePrefix' => 'tg_',
            //'enableSchemaCache' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['admin', 'author'],
        ],
    ],
    'params' => $params,
];
