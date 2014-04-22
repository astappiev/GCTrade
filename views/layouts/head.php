<?php
/**
 * "Head" содержимое основного frontend-шаблона.
 * @var yii\base\View $this Представление
 * @var array $params Основные параметры представления
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\helpers\Backgrounds;
use app\assets\AppAsset;
?>

<title><?= Html::encode($this->title).' — '.Yii::$app->params["siteName"] ?></title>
<?php $this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);
$this->registerMetaTag([
    'charset' => Yii::$app->charset
]);
$this->registerMetaTag([
    'http-equiv' => 'X-UA-Compatible',
    'content' => 'IE=edge'
]);
$this->registerMetaTag([
    'name' => 'viewport',
    'content' => 'width=device-width, initial-scale=1'
]);
$this->registerLinkTag([
    'href' => Yii::$app->getRequest()->baseUrl . '/favicon.png',
    'rel' => 'icon',
    'type' => 'image/png',
    'sizes' => '48x48'
]);
$this->registerLinkTag([
    'href' => Yii::$app->getRequest()->baseUrl . '/favicon.png',
    'rel' => 'shortcut icon',
    'type' => 'image/png',
    'sizes' => '48x48'
]);
$this->head();
AppAsset::register($this); ?>

<style>
    html > body{
        background: url(<?= Backgrounds::Rand(); ?>) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
</style>
