<?php
use yii\helpers\Html;

$this->title = 'API запросы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Массив с магазинами, папка с изображениями находится по адресу <code>http://gctrade.ru/images/shop/@logo</code>.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/shop'])), Yii::$app->urlManager->createAbsoluteUrl(['api/shop']), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/shop'])) ?></pre>
<pre>[
    {
        "alias": "nottingham",
        "name": "ТЦ Ноттингем",
        "about": "Магазин строительных материалов.",
        "description": null,
        "x_cord": -7700,
        "z_cord": -1750,
        "logo": "nottingham_l0cmw.jpg"
    }
]</pre>
    <br><br>


    <p>Стандартный массив пользователей, который доступен <code>http://srv1.greencubes.org/up/world/world</code>, различие лишь в CORS и чистке мусора.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/world'])), Yii::$app->urlManager->createAbsoluteUrl(['api/world']), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/world'])) ?></pre>
<pre>[
    {
        "name": "akka46",
        "z": -3099.0083263859,
        "y": 72.121296840539,
        "x": -9046.8513217186
    }
]</pre>
    <br><br>


    <p>Получение информации о одном из игроков, массива world.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/world']).'/:login'), Yii::$app->urlManager->createAbsoluteUrl(['api/world', 'login' => 'Galik']), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/world'])).'?login=:login' ?></pre>
    <p>Результат:</p>
<pre>{
    "status": 1,
    "player": {
        "name": "Galik",
        "z": -526.66999901533,
        "y": 49.074999988079,
        "x": -8202.5
    }
}</pre>
    <p>В случае если игрок не в сети:</p>
<pre>{
    "status": 0,
}</pre>
    <br><br>


    <p>Краткая информация и цены товара в системе GCTrade.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/item']).'/:id'), Yii::$app->urlManager->createAbsoluteUrl(['api/item', 'id' => 56.1]), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/item'])).'?id=:id' ?></pre>
<pre>{
    "id": 56.1,
    "name": "Декоративная алмазная руда",
    "description": null,
    "in_shop": 7,
    "cost": {
        "min": 10000,
        "avg": 10571.42857143,
        "max": 11000
    }
}</pre>
</div>