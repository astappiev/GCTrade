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
    <p>Для фильтрации результатов можно использовать запрос следующего вида.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/shop']).'/эварис'), Yii::$app->urlManager->createAbsoluteUrl(['api/shop', 'request' => 'эварис']), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/shop']).'?request=:name') ?></pre>
    <br><br>


    <p>Краткая информация и цены на товар, на основе GCTrade.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/item']).'/:request'), Yii::$app->urlManager->createAbsoluteUrl(['api/item', 'request' => 1]), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/item'])).'?request=:id' ?></pre>
<pre>[
    {
        "id": 1,
        "name": "Камень",
        "description": null,
        "in_shop": {
            "count": 17,
            "min": 0.9375,
            "avg": 1.15441176,
            "max": 1.25
        }
    }
]</pre>
    <p>Для фильтрации результатов можно использовать запрос следующего вида.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/item']).'/камень'), Yii::$app->urlManager->createAbsoluteUrl(['api/item', 'request' => 'камень']), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/item']).'?request=:name') ?></pre>
    <br><br>


    <p>Лицо пользователя, на основе его скина <strong><span class="green">Green</span>Cubes</strong>.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/head']).'/:login'), Yii::$app->urlManager->createAbsoluteUrl(['api/head', 'login' => 'astappev']), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/head'])).'?login=:login' ?></pre>
    <pre><?= Html::img('/api/head/astappev').' '.Html::img('/api/head/silbersamurai').' '.Html::img('/api/head/noob').' '.Html::img('/api/head/Kernel').' '.Html::img('/api/head/Rena4ka') ?></pre>
    <br><br>


    <p>Вывод (изображением) всех <strong><span class="green">Green</span>Cubes</strong> значков пользователя.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/badges']).'/:login'), Yii::$app->urlManager->createAbsoluteUrl(['api/badges', 'login' => 'venus']), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/badges'])).'?login=:login' ?></pre>
    <pre><?= Html::img('/api/badges/venus') ?></pre>
    <br><br>


    <p>Стандартный массив пользователей, который доступен <code>http://srv1.greencubes.org/up/world/world</code>, различие лишь в CORS и чистке мусора.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/world'])), Yii::$app->urlManager->createAbsoluteUrl(['api/world']), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/world'])) ?></pre>
<pre>[
    {
        "name": "AndBo",
        "cord": "-4444 65 924"
    }
]</pre>
    <p>Для получение информации об одном из игроков, массива world используйте запрос вида.</p>
    <pre><?= Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/world']).'/:login'), Yii::$app->urlManager->createAbsoluteUrl(['api/world', 'login' => 'Galik']), ['target' => '_blank']) ?></pre>
    <pre><?= 'GET '.Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/world'])).'?login=:login' ?></pre>
</div>