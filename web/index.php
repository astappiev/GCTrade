<?php
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('WEB_LOCAL') or define('WEB_LOCAL', false);
defined('YII_ENV') or define('YII_ENV', 'prod'); // test dev prod

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

if(WEB_LOCAL){
    $config = yii\helpers\ArrayHelper::merge(
        require(__DIR__ . '/../config/web.php'),
        require(__DIR__ . '/../config/web-local.php')
    );
} else {
    $config = yii\helpers\ArrayHelper::merge(
        require(__DIR__ . '/../config/web.php'),
        require(__DIR__ . '/../config/web-server.php')
    );
}

(new yii\web\Application($config))->run();
