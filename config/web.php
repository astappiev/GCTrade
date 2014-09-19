<?php
$server = require(__DIR__ . '/web-server.php');
$local = require(__DIR__ . '/web-local.php');
$debug = require(__DIR__ . '/web-debug.php');

$params = require(__DIR__ . '/params.php');
$rules = require(__DIR__ . '/rules.php');

$config = [
    'id' => 'gctrade',
    'name' => 'GCTrade',
    'language' => 'ru-RU',
    'sourceLanguage' => 'system',
    'charset' => 'utf-8',
    'timeZone' => 'Europe/Kiev',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'shop', 'users'],
    'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'), //???
    'components' => [
        'request' => [
            'baseUrl' => '',
            'cookieValidationKey' => 'VALIDATION_KEY'
        ],
        'assetManager' => [
			'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [YII_ENV_DEV ? 'dist/js/bootstrap.js' : 'dist/js/bootstrap.min.js'],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [YII_ENV_DEV ? 'dist/css/bootstrap.css' : 'dist/css/bootstrap.min.css'],
                ],
            ]
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'greencubes' => [
                    'class' => 'app\helpers\GreenCubesOAuth',
                    'clientId' => 'CLIENT_ID',
                    'clientSecret' => 'CLIENT_SECRET',
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\users\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['user/default/login'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => $rules,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
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
            'dsn' => 'mysql:host=DB1_HOST;dbname=DB1_NAME',
            'username' => 'DB1_USERNAME',
            'password' => 'DB1_PASSWORD',
            'charset' => 'utf8',
            'tablePrefix' => 'DB1_PREFIX',
        ],
        'db_analytics' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=DB2_HOST;dbname=DB2_NAME',
            'username' => 'DB2_USERNAME',
            'password' => 'DB2_PASSWORD',
            'charset' => 'utf8',
            'tablePrefix' => 'DB2_PREFIX',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/shop' => 'shop.php',
                        'app/users' => 'users.php',
                        'app/error' => 'error.php',
                    ],
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
    ],
    'modules' => [
        'shop' => [
            'class' => 'app\modules\shop\Modules',
        ],
        'users' => [
            'class' => 'app\modules\users\Modules',
        ],
    ],
    'params' => $params,
];

if(WEB_LOCAL) {
    $config = yii\helpers\ArrayHelper::merge($config, $local);
} else {
    $config = yii\helpers\ArrayHelper::merge($config, $server);
}

if (YII_DEBUG) {
    $config = yii\helpers\ArrayHelper::merge($config, $debug);
}

return $config;
