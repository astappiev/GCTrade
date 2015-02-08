<?php
use yii\helpers\Html;

$this->title = 'API GCTrade';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="well">
        <p>GCTrade разрабатывается максимально открыто и доступно, API является ключом к пониманию данного механизма. Абсолютно вся публичная информация о магазинах, ценах, аукционах является легко доступной и может быть использована где угодно и как угодно.</p>
        <p>API GCTrade постоянно развивается вместе из самим GCTrade и в случае необходимости ее функционал может быть расширен. Любые предложения или вопросы можно написать мне <a href="https://forum.greencubes.org/memberlist.php?mode=viewprofile&u=129522">на форуме</a>.</p>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">API GCTrade для работы с магазинами</h3>
        </div>
        <div class="panel-body">
            <h4>Данные об одном магазине</h4>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/shop', 'request' => '']) . '/:alias'), Yii::$app->urlManager->createAbsoluteUrl(['api/shop', 'request' => 'nottingham']), ['target' => '_blank']) ?></pre>
<pre>{
    "alias": "nottingham",
    "type": "0",
    "name": "ТЦ Ноттингем",
    "about": "Магазин строительных материалов.",
    "description": "&lt;p&gt;Администратор магазина Aceko.&lt;/p&gt;&lt;p&gt;При возникновении проблем пишите  в игре.&lt;/p&gt;&lt;p&gt;При желании купить оптом \"Белый Камень\", \"Камень\", \"Бревна\"(всех видов), красителей пишите в ЛС.&lt;/p&gt;",
    "subway": "Nott",
    "x_cord": "-7700",
    "z_cord": "-1750",
    "image_url": "http://gctrade.ru/images/shop/nottingham_l0cmw.jpg",
    "updated_at": "1421590259"
}</pre>
            <br>
            <h4>Данные обо всех магазинах</h4>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/shop-search'])), Yii::$app->urlManager->createAbsoluteUrl(['api/shop-search']), ['target' => '_blank']) ?></pre>
<pre>[
     {
         "alias": "twix",
         "type": "0",
         "name": "Гипермаркет TWIX",
         "about": "В наших магазинах вы найдете самые разные товары на всякий вкус и достаток, причем иногда на выбор разной цены или количества. Казино порадует вас разнообразием ставок и ценными призами.",
         "x_cord": "-7675",
         "z_cord": "-915",
         "image_url": "http://gctrade.ru/images/shop/twix_Atnkhh.png",
         "updated_at": "1421590259"
     },
     {
         "alias": "nottingham",
         "type": "0",
         "name": "ТЦ Ноттингем",
         "about": "Магазин строительных материалов.",
         "x_cord": "-7700",
         "z_cord": "-1750",
         "image_url": "http://gctrade.ru/images/shop/nottingham_l0cmw.jpg",
         "updated_at": "1421590259"
     },
     ...
]</pre>
            <p>Для фильтрации результатов можно использовать запрос следующего вида:</p>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/shop-search', 'request' => '']) . '/:name'), Yii::$app->urlManager->createAbsoluteUrl(['api/shop-search', 'request' => 'Гипермаркет TWIX']), ['target' => '_blank']) ?></pre>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">API GCTrade для работы с номенклатурой товаров</h3>
        </div>
        <div class="panel-body">
            <h4>Данные об одном товаре <span class="label label-default">New</span></h4>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/item', 'request' => '']) . '/:id'), Yii::$app->urlManager->createAbsoluteUrl(['api/item', 'request' => '1']), ['target' => '_blank']) ?></pre>
<pre>{
    "id": "1",
    "name": "Камень",
    "image_url": "http://gctrade.ru/images/items/1.png"
}</pre>
            <br>
            <h4>Данные обо всех товарах <span class="label label-default">New</span></h4>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/item-search'])), Yii::$app->urlManager->createAbsoluteUrl(['api/item-search']), ['target' => '_blank']) ?></pre>
<pre>[
     {
         "id": "1",
         "name": "Камень",
         "image_url": "http://gctrade.ru/images/items/1.png"
     },
     {
         "id": "2",
         "name": "Трава",
         "image_url": "http://gctrade.ru/images/items/2.png"
     }
     ...
]</pre>
            <p>Для фильтрации результатов можно использовать запрос следующего вида:</p>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/item-search', 'request' => '']) . '/:name'), Yii::$app->urlManager->createAbsoluteUrl(['api/item-search', 'request' => 'камень']), ['target' => '_blank']) ?></pre>

            <br>
            <h4>Данные о стоимости товаров</h4>


            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/item-cost'])), Yii::$app->urlManager->createAbsoluteUrl(['api/item-cost']), ['target' => '_blank']) ?></pre>
<pre>[
     {
         "id": "1",
         "name": "Камень",
         "image_url": "http://gctrade.ru/images/items/1.png"
         "in_shop": {
             "count": "20",
             "min": "0.7031",
             "avg": "1.12265625",
             "max": "1.2500"
         }
     },
     {
         "id": "2",
         "name": "Трава",
         "image_url": "http://gctrade.ru/images/items/2.png"
         "in_shop": {
             "count": "20",
             "min": "0.7031",
             "avg": "1.12265625",
             "max": "1.2500"
         }
     }
     ...
]</pre>
            <p>Для фильтрации результатов можно использовать запрос следующего вида:</p>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/item-cost', 'request' => '']) . '/:name'), Yii::$app->urlManager->createAbsoluteUrl(['api/item-cost', 'request' => 'камень']), ['target' => '_blank']) ?></pre>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">API GCTrade для работы с товаром</h3>
        </div>
        <div class="panel-body">
            <h4>Данные о наличии товара</h4>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/goods', 'request' => '']) . '/:id'), Yii::$app->urlManager->createAbsoluteUrl(['api/goods', 'request' => '1']), ['target' => '_blank']) ?></pre>
<pre>{
     "id": "1",
     "name": "Камень",
     "image_url": "http://gctrade.ru/images/items/1.png"
     "in_shop": [
         {
             "price_sell": "45",
             "price_buy": "20",
             "stuck": "64",
             "shop": {
                 "name": "Зелёный Гоблин",
                 "image_url": "http://gctrade.ru/images/shop/546552327400f.png",
                 "shop_url": "http://gctrade.ru/shop/GreenGoblin"}
             }
         },
         {
             "price_sell": "48",
             "price_buy": "30",
             "stuck": "64",
             "shop": {
             "name": "ТЦ Ноттингем",
                 "image_url": "http://gctrade.ru/images/shop/nottingham_l0cmw.jpg",
                 "shop_url":"http://gctrade.ru/shop/nottingham"
             }
         },
         ...
     ]
}</pre>
            <br>
            <h4>Данные о наличии всех товаров</h4>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/goods-search'])), Yii::$app->urlManager->createAbsoluteUrl(['api/goods-search']), ['target' => '_blank']) ?></pre>
<pre>[
     {
         "id": "1",
         "name": "Камень",
         "image_url": "http://gctrade.ru/images/items/1.png"
         "in_shop": [
             {
                 "price_sell": "45",
                 "price_buy": "20",
                 "stuck": "64",
                 "shop": {
                     "name": "Зелёный Гоблин",
                     "image_url": "http://gctrade.ru/images/shop/546552327400f.png",
                     "shop_url": "http://gctrade.ru/shop/GreenGoblin"}
                 }
             },
             {
                 "price_sell": "48",
                 "price_buy": "30",
                 "stuck": "64",
                 "shop": {
                     "name": "ТЦ Ноттингем",
                     "image_url": "http://gctrade.ru/images/shop/nottingham_l0cmw.jpg",
                     "shop_url":"http://gctrade.ru/shop/nottingham"
                 }
             },
             ...
         ]
     },
     ...
]</pre>
            <p>Для фильтрации результатов можно использовать запрос следующего вида:</p>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/goods-search', 'request' => '']) . '/:name'), Yii::$app->urlManager->createAbsoluteUrl(['api/goods-search', 'request' => 'камень']), ['target' => '_blank']) ?></pre>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">API GCTrade для работы с пользователями</h3>
        </div>
        <div class="panel-body">
            <h4>Получить регионы текущего пользователя <span class="label label-danger">Необходима авторизация на GCTrade</span></h4>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/user-regions'])), Yii::$app->urlManager->createAbsoluteUrl(['api/user-regions']), ['target' => '_blank']) ?></pre>
<pre>[
    {
        "name": "GCRC_CrystalCity1",
        "rights": [
            "full"
        ],
        "coordinates": {
            "first": "-5531 63 190",
            "second": "-5524 127 214"
        }
    },
    {
        "name": "GCRC_BA-Plaza_owner",
        "rights": [
            "full"
        ],
        "coordinates": {
            "first": "-8276 65 -906",
            "second": "-8053 127 -898"
        }
    },
    ...
]</pre>
            <br>
            <h4>Получить расположение всех пользователей онлайн</h4>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/user-world'])), Yii::$app->urlManager->createAbsoluteUrl(['api/user-world']), ['target' => '_blank']) ?></pre>
<pre>[
     {
         "username": "Aceko",
         "coordinates": "-7540 46 -2092"
     },
     {
         "username": "Aleksandr1977",
         "coordinates": "-8044 64 -301"
     },
     ...
]</pre>
            <br>
            <h4>Получить текущее положение пользователя <span class="label label-danger">Пользователь должен быть онлайн</span></h4>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/user-world', 'request' => '']) . '/:username'), Yii::$app->urlManager->createAbsoluteUrl(['api/user-world', 'request' => 'GCMap']), ['target' => '_blank']) ?></pre>
<pre>{
     "username": "GCMap",
     "coordinates": "-10346 1 -2785"
}</pre>
            <br>
            <h4>Лицо пользователя, на основе его скина <strong><span class="green">Green</span>Cubes</strong></h4>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/user-head', 'request' => '']) . '/:username'), Yii::$app->urlManager->createAbsoluteUrl(['api/user-head', 'request' => 'GCMap']), ['target' => '_blank']) ?></pre>
            <pre><?= Html::img('/api/user/head/astappev').' '.Html::img('/api/user/head/silbersamurai').' '.Html::img('/api/user/head/noob').' '.Html::img('/api/user/head/Kernel').' '.Html::img('/api/user/head/Rena4ka') ?></pre>
            <br>
            <h4>Вывод (изображением) всех <strong><span class="green">Green</span>Cubes</strong> значков пользователя</h4>
            <pre><?= 'GET ' . Html::a(Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['api/user-badges', 'request' => '']).'/:login'), Yii::$app->urlManager->createAbsoluteUrl(['api/user-badges', 'request' => 'venus']), ['target' => '_blank']) ?></pre>
            <pre><?= Html::img('/api/user/badges/venus') ?></pre>
        </div>
    </div>
</div>