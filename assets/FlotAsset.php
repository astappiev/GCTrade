<?php
namespace app\assets;

use yii\web\AssetBundle;

class FlotAsset extends AssetBundle
{
    public $sourcePath = '@bower/flot';
    public $css = [
    ];
    public $js = [
        'jquery.flot.js',
        'jquery.flot.time.js',
        'jquery.flot.selection.js',
        'jquery.flot.resize.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
