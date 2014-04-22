<?php
use yii\widgets\Breadcrumbs;
use app\widgets\Alert;

$this->title = 'Карта магазинов';
$this->params['breadcrumbs'][] = $this->title;
?>

<iframe class="frame-full" src="http://gcmap.ru/iframe.html?layers=shops" frameborder="0"></iframe>
<!--
<div class="container">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
    <?= Alert::widget() ?>
</div> -->