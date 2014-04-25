<?php
use yii\helpers\Html;
use app\models\Shop;
use yii\bootstrap\Nav;

$this->title = 'Редактировать магазины';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content shop-list edit-shop">
	<h1><?= Html::encode($this->title) ?></h1>

    <?php foreach (Shop::find()->where(['owner' => Yii::$app->user->id])->orderBy(['updated_at' => SORT_ASC])->all() as $shop): ?>

        <div class="well" id="<?= $shop->id ?>">
            <a href="<?= Yii::$app->urlManager->createUrl(['shop/page', 'alias' => $shop->alias]) ?>"><img src="/images/shop/<?= ($shop->logo)?$shop->logo:'nologo.png' ?>" alt="<?= $shop->name ?>" class="img-rounded"></a>
            <div class="info">
                <h3><a href="<?= Yii::$app->urlManager->createUrl(['shop/page', 'alias' => $shop->alias]) ?>"><?= $shop->name ?></a></h3>
                <p><?= $shop->about ?></p>
                <?= '<p>Последнее обновление: '.gmdate("Y-m-d H:i:s", $shop->updated_at).'</p>' ?>
                <?php echo Nav::widget([
                    'options' => ['class' => 'nav nav-pills edit-shop'],
                    'items' => [
                        ['label' => 'Редактировать товар', 'url' => ['/shop/item/', 'alias' => $shop->alias]],
                        ['label' => 'Редактировать лого', 'url' => ['/shop/logo', 'alias' => $shop->alias]],
                        ['label' => 'Редактировать информацию', 'url' => ['/shop/info', 'alias' => $shop->alias]],
                        ['label' => 'Удалить', 'url' => ['/shop/delete', 'alias' => $shop->alias]],
                    ],
                ]); ?>
            </div>
        </div>

    <?php endforeach; ?>
</div>