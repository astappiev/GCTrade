<?php
namespace app\assets;

use yii\web\AssetBundle;

class MapAsset extends AssetBundle
{
    public $sourcePath = '@bower/leaflet-dist';
    public $css = [
        'leaflet.css',
    ];
    public $js = [
        'leaflet.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
