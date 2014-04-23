<?php
use yii\helpers\Html;
?>

<p>Привет, мы обнаружили что некоторые товары из ваших магазинов отмечены как неактуальные.</p>
<p>В магазине <?= Html::encode($name) ?>, отмечено <?= Html::encode($count) ?> товаров.</p>
<p>Просим отреагировать и устранить проблему.</p>
<p>Отписаться от данной функции можно в <?= Html::a(Html::encode('личном кабинете'), 'http://gctrade.ru/user/edit') ?>.</p>