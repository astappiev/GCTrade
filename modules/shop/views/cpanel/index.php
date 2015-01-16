<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */
$this->title = 'Ваши Магазины';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content shop-cpanel">

	<h1><?= Html::encode($this->title) ?></h1>

    <?php echo ListView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'firstPageLabel' => '<span class="glyphicon glyphicon-fast-backward"></span>',
            'lastPageLabel' => '<span class="glyphicon glyphicon-fast-forward"></span>',
            'nextPageLabel' => '<span class="glyphicon glyphicon-step-forward"></span>',
            'prevPageLabel' => '<span class="glyphicon glyphicon-step-backward"></span>',
        ],
        'layout' => "<div class=\"posts clearfix\">{items}</div>\n{pager}\n{summary}",
        'options' => [
            'tag' => 'div',
            'class' => 'shop-list',
        ],
        'itemOptions' => [
            'tag' => 'div',
            'class' => 'post',
        ],
        'itemView' => '_list_item',
    ]); ?>

    <?php
    /*if(empty($shops))
    {
        echo '<div class="alert alert-danger" role="alert">У тебя все еще нет своего магазина. Если это не так, ты '.Html::a("должен его добавить", Url::toRoute('shop/cpanel/create')).'!</div>';
    }*/
    ?>

</div>