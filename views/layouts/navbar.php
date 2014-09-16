<?php

use yii\bootstrap\NavBar;
use app\helpers\Nav;
use app\modules\users\models\Message;

NavBar::begin([
    'brandLabel' => 'GCTrade',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

$mainMenu = [
    ['label' => 'Магазины', 'linkOptions' => ['class' => 'intro-shop'], 'items' => [
        ['label' =>  'Все магазины', 'url' => ['/shop/default/index']],
        ['label' => 'Магазины на карте', 'url' => ['/shop/default/map']],
        '<li class="divider"></li>',
        ['label' => 'Все товары', 'url' => ['/shop/item/full']],
        ['label' => 'Товары в наличии', 'url' => ['/shop/item/index']],
    ]],
    //['label' => 'Аукционы', 'linkOptions' => ['class' => 'intro-auction'], 'url' => ['/auction/index']],
    ['label' => 'Инструменты', 'linkOptions' => ['class' => 'intro-tools'], 'items' => [
        ['label' => ' Наблюдение за активностью', 'icon' => 'search', 'url' => ['/see/index']],
        ['label' => ' Карта регионов пользователя', 'icon' => 'flag', 'url' => ['/maps/user']],
        ['label' => ' Калькулятор регионов', 'icon' => 'resize-full', 'url' => ['/site/calc']],
        ['label' => ' Статистика ЖД', 'icon' => 'stats', 'url' => ['/site/rail']],
        '<li class="divider"></li>',
        '<li role="presentation" class="dropdown-header">Наши друзья</li>',
        ['label' => 'Расчёты связаные с белым камнем', 'linkOptions' => ['target' => '_blank'], 'url' => 'http://raschet.gctrade.ru/'],
    ]],
    ['label' => 'Статистика', 'linkOptions' => ['class' => 'intro-economy'], 'url' => ['/site/statistics']],
];

if (!Yii::$app->user->isGuest) {

    $userMenu = [
        /*['label' => ' ', 'icon' => 'plus', 'items' => [
            ['label' => 'Добавить магазин', 'url' => ['/shop/cpanel/create']],
            ['label' => 'Добавить лот', 'url' => ['/auction/cpanel/create']],
        ]],
        ['label' => ' ', 'icon' => 'cog', 'items' => [
            ['label' => 'Редактировать магазины', 'url' => ['/shop/cpanel/edit']],
            ['label' => 'Редактировать лоты', 'url' => ['/auction/cpanel/create']],
        ]],*/
        ['label' => ' ', 'icon' => 'plus', 'url' => ['/shop/cpanel/create']],
        ['label' => ' ', 'icon' => 'cog', 'url' => ['/shop/cpanel/index']],
        ['label' => ' ', 'icon' => 'comment', 'url' => ['/users/message/index'], 'badge' => Message::getCount()],
        ['label' => ' '.Yii::$app->user->identity->username, 'icon' => 'user', 'linkOptions' => ['class' => 'intro-user'], 'items' => [
            ['label' => 'Твой профиль', 'url' => ['/users/default/index']],
            ['label' => 'Настройки профиля', 'url' => ['/users/default/edit']],
            ['label' => 'Выйти', 'url' => ['/users/default/logout']],
        ]]
    ];

} else  {

    $userMenu = [[
        'label' => 'Войти',
        'linkOptions' => ['id' => 'gclogin', 'class' => 'intro-user'],
        'url' => ['/users/default/auth', 'authclient' => 'greencubes']
    ]];
    $this->registerJs("$('a#gclogin').on('click', function(e) {
        e.preventDefault();

        GCAuthPopup = window.open(this.href, 'gc_auth', 'directories=yes');
        GCAuthPopup.focus();
        this.data('GCAuthPopup', GCAuthPopup);
    });");

}

echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => $mainMenu,
    'activateParents' => true,
]);
echo Nav::widget([
    'options' => ['id' => 'uid-auth', 'class' => 'navbar-nav navbar-right'],
    'items' => $userMenu,
    'activateParents' => true,
]);

NavBar::end();