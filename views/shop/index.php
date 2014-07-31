<?php
use yii\helpers\Html;
use app\models\Shop;

$this->title = 'Каталог магазинов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content shop-list view">
	<h1><?= Html::encode($this->title) ?></h1>

    <?php foreach (Shop::find()->orderBy(['updated_at' => SORT_DESC])->all() as $shop): ?>

        <div class="well">
            <a href="<?= Yii::$app->urlManager->createUrl(['shop/view', 'alias' => $shop->alias]) ?>"><img src="<?= $shop->getLogo() ?>" alt="<?= $shop->name ?>" class="img-rounded" /></a>
            <div class="info">
                <h3><a href="<?= Yii::$app->urlManager->createUrl(['shop/view', 'alias' => $shop->alias]) ?>"><?= $shop->name ?></a></h3>
                <p><?= $shop->about ?></p>
                <?php if($shop->subway) echo '<p>Станция метро: /go '.$shop->subway.'</p>' ?>
                <?php if($shop->x_cord && $shop->z_cord) echo '<p id="cord" data-x="'.$shop->x_cord.'" data-z="'.$shop->x_cord.'">Координаты: X: '.$shop->x_cord.', Z: '.$shop->z_cord.'</p>' ?>
                <?= '<p>Последнее обновление: <span class="label label-info">'.gmdate("Y-m-d H:i", $shop->updated_at).'</span></p>' ?>
            </div>
        </div>

    <?php endforeach; ?>
</div>