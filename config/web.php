<?php
$server = require(__DIR__ . '/web-server.php');
$debug = require(__DIR__ . '/web-debug.php');

$params = require(__DIR__ . '/params.php');
$rules = require(__DIR__ . '/rules.php');

$config = [
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
        'request' => [
            'enableCsrfValidation' => true,
            'enableCookieValidation' => true,
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
                'from' => $params['supportEmail'],
            ],
        ],
        'log' => [
            'traceLevel' => 0,
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
            'dsn' => 'mysql:host=localhost;dbname=gctrade',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => 'tg_',
        ],
        'db_analytics' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=gctrade_analytics',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => 'tg_',
        ],
    ],
    'params' => $params,
];

if(!WEB_LOCAL){
    $config = yii\helpers\ArrayHelper::merge($config, $server);
}

if (YII_DEBUG) {
    $config = yii\helpers\ArrayHelper::merge($config, $debug);
}

return $config;
