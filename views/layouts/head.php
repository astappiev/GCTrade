<?php
/**
 * "Head" содержимое основного frontend-шаблона.
 * @var yii\web\View $this Представление
 * @var array $params Основные параметры представления
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\helpers\Backgrounds;
use app\assets\AppAsset;
?>
<meta charset="<?= Yii::$app->charset ?>"/>
<title><?= Html::encode($this->title).' — '.Yii::$app->params["siteName"] ?></title>
<meta name="viewport" content="width=device-width, initial-scale=0.9">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link type="image/png" href="/favicon.png" rel="icon" sizes="48x48">
<link type="image/png" href="/favicon.png" rel="shortcut icon" sizes="48x48">
<link type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu|Open+Sans&subset=latin,cyrillic" rel="stylesheet">

<?php
if(!Yii::$app->user->isGuest) $this->registerJS("var username = '".Yii::$app->user->identity->username."';", $this::POS_END);

echo Html::csrfMetaTags();
$this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);
$this->head();
AppAsset::register($this);
?>

<style>
    html > body{
        background: url(<?= Backgrounds::Rand(); ?>) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
</style>
