<?php
$params = require(__DIR__ . '/params.php');

$config = [
    'name' => 'GCTrade',
    'language'=>'ru-RU',
    'sourceLanguage' => 'system',
    'timeZone' => 'Europe/Kiev',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=gctrade',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'tablePrefix' => 'tg_',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
        ],
        'db_analytics' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=gcstat',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'tablePrefix' => 'tg_',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['admin', 'moder', 'author', 'user'],
            'itemFile' => '@app/modules/users/rbac/items.php',
            'assignmentFile' => '@app/modules/users/rbac/assignments.php',
            'ruleFile' => '@app/modules/users/rbac/rules.php',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                'file' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            //'useFileTransport' => true,
            'viewPath' => '@app/mails',
            'messageConfig' => [
                'from' => $params['supportEmail'],
            ],
        ],
    ],
    'modules' => [
        'shop' => [
            'class' => 'app\modules\shop\Modules',
        ],
        'auction' => [
            'class' => 'app\modules\auction\Modules',
        ],
        'users' => [
            'class' => 'app\modules\users\Modules',
        ],
    ],
    'params' => $params,
];

if(YII_ENV_PROD){
    $common_prod = require(__DIR__ . '/common-prod.php');
    $config = yii\helpers\ArrayHelper::merge($config, $common_prod);
}

return $config;