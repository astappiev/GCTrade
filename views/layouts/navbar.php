<?php
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;

NavBar::begin([
    'brandLabel' => 'GCTrade',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

$menuItems = [
    ['label' => 'Магазины', 'linkOptions' => ['class' => 'intro-shop'], 'items' => [
        ['label' => 'Все магазины', 'url' => ['/shop/index']],
    ]],
    ['label' => 'Товар', 'linkOptions' => ['class' => 'intro-item'], 'items' => [
        ['label' => 'Все товары', 'url' => ['/item/full']],
        ['label' => 'Товары в наличии', 'url' => ['/item/index']],
    ]],
    /*['label' => 'Объявления', 'linkOptions' => ['class' => 'intro-board'], 'items' => [
        ['label' => 'Все объявления', 'url' => ['/board/index']],
    ]],*/
    ['label' => 'Экономика', 'linkOptions' => ['class' => 'intro-economy'], 'url' => ['/site/economy']],
    ['label' => 'Карта', 'linkOptions' => ['class' => 'intro-maps'], 'url' => ['/maps/index']],
    ['label' => 'Прочее', 'linkOptions' => ['class' => 'intro-other'], 'items' => [
        ['label' => 'Наблюдение за активностью', 'url' => ['/see/index']],
        ['label' => 'Статистика ЖД', 'url' => ['/site/rail']],
        ['label' => 'Калькулятор регионов', 'url' => ['/site/calc']],
    ]],
];

$menuItemsUser = [
    ['label' => 'Магазины', 'linkOptions' => ['class' => 'intro-shop'], 'items' => [
        ['label' => 'Все магазины', 'url' => ['/shop/index']],
        ['label' => 'Управление магазинами', 'url' => ['/shop/edit']],
        ['label' => 'Добавить магазин', 'url' => ['/shop/create']],
    ]],
    ['label' => 'Товар', 'linkOptions' => ['class' => 'intro-item'], 'items' => [
        ['label' => 'Все товары', 'url' => ['/item/full']],
        ['label' => 'Товары в наличии', 'url' => ['/item/index']],
    ]],
    /*['label' => 'Объявления', 'linkOptions' => ['class' => 'intro-board'], 'items' => [
        ['label' => 'Все объявления', 'url' => ['/board/index']],
        ['label' => 'Разместить объявление', 'url' => ['/board/add']],
        ['label' => 'Управление объявлениями', 'url' => ['/board/edit']],
    ]],*/
    ['label' => 'Экономика', 'linkOptions' => ['class' => 'intro-economy'], 'url' => ['/site/economy']],
    ['label' => 'Карта', 'linkOptions' => ['class' => 'intro-maps'], 'url' => ['/maps/index']],
    ['label' => 'Прочее', 'linkOptions' => ['class' => 'intro-other'], 'items' => [
        ['label' => 'Наблюдение за активностью', 'url' => ['/see/index']],
        ['label' => 'Статистика ЖД', 'url' => ['/site/rail']],
        ['label' => 'Калькулятор регионов', 'url' => ['/site/calc']],
    ]],
];

if (Yii::$app->user->isGuest) {
    $menuUser[] = ['label' => 'Зарегистрироваться', 'linkOptions' => ['class' => 'intro-register'], 'url' => ['/user/signup']];
    $menuUser[] = ['label' => 'Войти', 'linkOptions' => ['class' => 'intro-login'], 'url' => ['/user/login']];
} else {
    $menuUser[] = ['label' => Yii::$app->user->identity->username, 'linkOptions' => ['class' => 'intro-user'], 'items' => [
        ['label' => 'Мой профиль', 'url' => ['/user/index']],
        ['label' => 'Настройки профиля', 'url' => ['/user/edit']],
        ['label' => 'Изменение пароля', 'url' => ['/user/password']],
        '<li class="divider"></li>',
        ['label' => 'Обратная связь', 'url' => ['/site/contact']],
        ['label' => 'API', 'url' => ['/api/index']],
        ['label' => 'Выйти', 'url' => ['/user/logout']],
    ]];
}

echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => (Yii::$app->user->isGuest)?$menuItems:$menuItemsUser,
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuUser,
]);
NavBar::end();
if (Yii::$app->user->isGuest) echo '<a href="https://twitter.com/search?src=typd&q=%23unitedforukraine" target="_blank"><div id="ukraine"></div></a>';