<?php
namespace app\web\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot/web';
    public $baseUrl = '@web';
    public $css = [
        'css/gctrade.min.css',
    ];
    public $js = [
        'js/gctrade.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
