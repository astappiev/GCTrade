<?php
namespace app\extensions\tinymce\assets;

use yii\web\AssetBundle;

/**
 * Менеджер ресурсов
 */
class TinymceAsset extends AssetBundle
{
	public $sourcePath = '@app/extensions/tinymce/assets/vendor/tinymce/js/tinymce';
	public $js = [
	    'tinymce.min.js'
	];
	public $depends = [
		'yii\web\JqueryAsset',
		'yii\web\YiiAsset'
	];
}