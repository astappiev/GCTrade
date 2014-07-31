<?php
use yii\helpers\Html;

$this->title = 'API запросы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Массив с магазинами, на основе GCTrade.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/shop'])), Yii::$app->urlManager->createAbsoluteUrl(['api/shop']), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/shop'])) ?></pre>
<pre>[
    {
        "alias": "evaris",
        "name": "Эварис",
        "about": "Добро пожаловать в казино \"Эварис\"! Здесь есть девять раздатчиков с ценными призами и магазин модификаторов свечения.",
        "description": "На первом этаже казино находится девять раздатчиков с самыми разнообразными призами и ставками на любой вкус и карман. Все призы ценные, поэтому даже в самый неудачный день вы не уйдете с пустыми руками.\r\nНа втором этаже разместился маленький магазинчик, в котором продаются модификаторы свечения (базовые 50 и 60%), а так же скупаются усиления, изъятия и очищения. Помимо этого здесь частенько можно найти и другие товары - разные шапки, леденцы, свитки, батончики, фейерверки и прочие полезные мелочи. Эти вещи появляются время от времени, в основном в период новых тиражей сундуков или ввода нового контента.",
        "subway": "canterlot",
        "x_cord": -8050,
        "z_cord": -1500,
        "logo_url": "http://gctrade.ru/images/shop/evaris_6jwi6.jpg",
        "updated_at": 1398459841
    }
]</pre>
    <br><br>


    <p>Краткая информация и цены на товар, на основе GCTrade.</p>
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
    <br><br>


    <p>Последние 100 записей о состоянии экономики GreenCubes, на основе GCTrade.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/economy'])), Yii::$app->urlManager->createAbsoluteUrl(['api/economy']), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/economy'])) ?></pre>
<pre>[
    {
        "date": "2014-07-30 23:15:06",
        "value": 4827453
    },
    {
        "date": "2014-07-30 22:14:59",
        "value": 4789015
    },
    {
        "date": "2014-07-30 21:12:52",
        "value": 5377462
    }
]</pre>
    <br><br>


    <p>Лицо пользователя, на основе его скина GreenCubes.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/skins']).'/:login'), Yii::$app->urlManager->createAbsoluteUrl(['api/skins', 'login' => 'astappev']), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/skins'])).'?login=:login' ?></pre>

    <pre><?= Html::img('/api/skins/astappev').' '.Html::img('/api/skins/silbersamurai').' '.Html::img('/api/skins/noob').' '.Html::img('/api/skins/Kernel').' '.Html::img('/api/skins/Rena4ka') ?></pre>
    <br><br>


    <p>Стандартный массив пользователей, который доступен <code>http://srv1.greencubes.org/up/world/world</code>, различие лишь в CORS и чистке мусора.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/world'])), Yii::$app->urlManager->createAbsoluteUrl(['api/world']), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/world'])) ?></pre>
<pre>[
    {
        "name": "AndBo",
        "cord": "-4444 65 924"
    },
    {
        "name": "Castor",
        "cord": "-7856 74 -2369"
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
        "cord": "-8202 49 -526"
    }
}</pre>
    <p>В случае если игрок не в сети:</p>
<pre>{
    "status": 0,
}</pre>
</div>