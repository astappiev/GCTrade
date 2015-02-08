<?php
Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
$common = require(__DIR__ . '/common.php');

$config = [
    'id' => 'gctrade-console',
    'controllerNamespace' => 'app\commands',
];

return yii\helpers\ArrayHelper::merge($config, $common);