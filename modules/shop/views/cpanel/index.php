<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\shop\models\Shop;

$this->title = 'Магазины';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content shop-list edit-shop">
	<h1><?= Html::encode($this->title) ?></h1>

    <?php
    $shops = Shop::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['updated_at' => SORT_ASC])->all();

    if(empty($shops))
    {
        echo '<div class="alert alert-danger" role="alert">У тебя все еще нет своего магазина. Если это не так, ты '.Html::a("должен его добавить", Url::toRoute('shop/cpanel/create')).'!</div>';
    }
    ?>

    <?php foreach ($shops as $shop): ?>

        <div class="well" id="<?= $shop->id ?>">
            <a href="<?= Yii::$app->urlManager->createUrl(['shop/default/view', 'alias' => $shop->alias]) ?>"><img
                    src="<?= $shop->logo ?>" alt="<?= $shop->name ?>" class="img-rounded" /></a>
            <div class="info">
                <h3><a href="<?= Yii::$app->urlManager->createUrl(['shop/default/view', 'alias' => $shop->alias]) ?>"><?= $shop->name ?></a></h3>
                <p><?= $shop->about ?></p>
                <?= '<p>Последнее обновление: '.gmdate("Y-m-d H:i:s", $shop->updated_at).'</p>' ?>
                <?= Html::a('Редактировать товар', ['edit', 'alias' => $shop->alias], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Редактировать информацию', ['update', 'alias' => $shop->alias], ['class' => 'btn btn-info']) ?>
                <?= Html::a('Удалить', ['delete', 'alias' => $shop->alias], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы действительно хотите удалить магазин?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>

    <?php endforeach; ?>
</div>