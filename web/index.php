<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('WEB_LOCAL') or define('WEB_LOCAL', false);
defined('YII_ENV') or define('YII_ENV', 'prod'); // test dev prod

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
(new yii\web\Application($config))->run();