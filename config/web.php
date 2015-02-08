<?php
$common = require(__DIR__ . '/common.php');
$rules = require(__DIR__ . '/rules.php');

$config = [
    'id' => 'gctrade',
    'charset' => 'utf-8',
    'bootstrap' => ['shop', 'auction', 'users'],
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
                    'js' => [YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js'],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css'],
                ],
            ]
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'greencubes' => [
                    'class' => 'app\modules\users\components\GreencubesAuth',
                    'clientId' => 'CLIENT_ID',
                    'clientSecret' => 'CLIENT_SECRET',
                ],
            ],
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
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/shop' => 'shop.php',
                        'app/error' => 'error.php',
                    ],
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
    ],
];

$config = yii\helpers\ArrayHelper::merge($config, $common);

if(YII_ENV_PROD) {
    $prod = require(__DIR__ . '/web-prod.php');
    $config = yii\helpers\ArrayHelper::merge($config, $prod);
} else {
    $dev = require(__DIR__ . '/web-dev.php');
    $config = yii\helpers\ArrayHelper::merge($config, $dev);
}

if (YII_DEBUG) {
    $debug = require(__DIR__ . '/web-debug.php');
    $config = yii\helpers\ArrayHelper::merge($config, $debug);
}

return $config;
