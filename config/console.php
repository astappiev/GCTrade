<?php
Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$server = require(__DIR__ . '/web-server.php');
$local = require(__DIR__ . '/web-local.php');

$config = [
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
            'viewPath' => '@app/mails',
            'messageConfig' => [
                'from' => $params['supportEmail'],
            ],
        ],
    ],
    'params' => $params,
];

if(WEB_LOCAL){
    $config = yii\helpers\ArrayHelper::merge($config, $local);
} else {
    $config = yii\helpers\ArrayHelper::merge($config, $server);
}

return $config;