<?php
namespace app\assets;

use yii\web\AssetBundle;

class SalvattoreAsset extends AssetBundle
{
    public $sourcePath = '@bower/salvattore/dist';
    public $js = [
        'salvattore.min.js',
    ];
}
