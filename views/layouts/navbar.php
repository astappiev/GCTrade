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
    ['label' => 'Магазины', 'linkOptions' => ['class' => 'intro-shop'], 'url' => ['/shop/index']],
    //['label' => 'Аукционы', 'linkOptions' => ['class' => 'intro-auction'], 'url' => ['/auction/index']],
    ['label' => 'Товар', 'linkOptions' => ['class' => 'intro-item'], 'items' => [
        ['label' => 'Все товары', 'url' => ['/item/full']],
        ['label' => 'Товары в наличии', 'url' => ['/item/index']],
    ]],
    ['label' => 'Экономика', 'linkOptions' => ['class' => 'intro-economy'], 'url' => ['/site/economy']],
    ['label' => 'Карта', 'linkOptions' => ['class' => 'intro-maps'], 'url' => ['/maps/index']],
    ['label' => 'Инструменты', 'linkOptions' => ['class' => 'intro-other'], 'items' => [
        ['label' => 'Наблюдение за активностью', 'url' => ['/see/index']],
        ['label' => 'Статистика ЖД', 'url' => ['/site/rail']],
        ['label' => 'Калькулятор регионов', 'url' => ['/site/calc']],
        ['label' => 'Расчёты связаные с белым камнем', 'linkOptions' => ['target' => '_blank'], 'url' => ['/site/raschet']],
    ]],
];

$menuUser = [
    ['label' => 'Войти', 'linkOptions' => ['class' => 'intro-user'],  'url' => ['/user/login']],
];

if (!\Yii::$app->user->isGuest) {
    $menuItems[0] = ['label' => 'Магазины', 'linkOptions' => ['class' => 'intro-shop'], 'items' => [
        ['label' => 'Все магазины', 'url' => ['/shop/index']],
        ['label' => 'Управление магазинами', 'url' => ['/shop/edit']],
        ['label' => 'Добавить магазин', 'url' => ['/shop/create']],
    ]];
    /*$menuItems[1] = ['label' => 'Аукционы', 'linkOptions' => ['class' => 'intro-auction'], 'items' => [
        ['label' => 'Лоты', 'url' => ['/auction/index']],
        ['label' => 'Добавить лот', 'url' => ['/auction/create']],
    ]];*/
    $menuItems[3] = ['label' => 'Карта', 'linkOptions' => ['class' => 'intro-maps'], 'items' => [
        ['label' => 'Карта магазинов', 'url' => ['/maps/index']],
        ['label' => 'Карта регионов пользователя', 'url' => ['/maps/user']]
    ]];


    $menuUser[0] = ['label' => Yii::$app->user->identity->username, 'linkOptions' => ['class' => 'intro-user'], 'items' => [
        ['label' => 'Твой профиль', 'url' => ['/user/index']],
        ['label' => 'Настройки профиля', 'url' => ['/user/edit']],
        //['label' => 'Изменение пароля', 'url' => ['/user/password']],
        ['label' => 'Выйти', 'url' => ['/user/logout']],
        '<li class="divider"></li>',
        ['label' => 'API', 'url' => ['/api/index']],
        '<li class="divider"></li>',
        ['label' => 'Помощь проекту', 'url' => ['/site/donate']],
        ['label' => 'Форум поддержки', 'linkOptions' => ['target' => '_blank'], 'url' => ['/site/forum']],
    ]];
} else if(\Yii::$app->has('authClientCollection')) {
    $menuUser[0] = ['label' => 'Войти', 'linkOptions' => ['id' => 'gclogin', 'class' => 'intro-user'],  'url' => ['/user/auth?authclient=greencubes']];
    $this->registerJs("$('a#gclogin').on('click', function(e) {
        e.preventDefault();

        GCAuthPopup = window.open(this.href, 'gc_auth', 'directories=yes');
        GCAuthPopup.focus();
        this.data('GCAuthPopup', GCAuthPopup);
    });");
}

echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => $menuItems,
]);
echo Nav::widget([
    'options' => ['id' => 'uid-auth', 'class' => 'navbar-nav navbar-right'],
    'items' => $menuUser,
]);

NavBar::end();