<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Shop;
use yii\bootstrap\Nav;

$this->title = 'Управление';
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content shop-list edit-shop">
	<h1><?= Html::encode($this->title) ?></h1>

    <?php
    $shops = Shop::find()->where(['owner' => Yii::$app->user->id])->orderBy(['updated_at' => SORT_ASC])->all();

    if(empty($shops))
    {
        echo '<div class="alert alert-danger" role="alert">У тебя все еще нет своего магазина. Если это не так, ты '.Html::a("должен его добавить", Url::toRoute('shop/create')).'!</div>';
    }
    ?>

    <?php foreach ($shops as $shop): ?>

        <div class="well" id="<?= $shop->id ?>">
            <a href="<?= Yii::$app->urlManager->createUrl(['shop/view', 'alias' => $shop->alias]) ?>"><img src="<?= $shop->getLogo() ?>" alt="<?= $shop->name ?>" class="img-rounded" /></a>
            <div class="info">
                <h3><a href="<?= Yii::$app->urlManager->createUrl(['shop/view', 'alias' => $shop->alias]) ?>"><?= $shop->name ?></a></h3>
                <p><?= $shop->about ?></p>
                <?= '<p>Последнее обновление: '.gmdate("Y-m-d H:i:s", $shop->updated_at).'</p>' ?>
                <?= Nav::widget([
                    'options' => ['class' => 'nav nav-pills edit-shop'],
                    'items' => [
                        ['label' => 'Редактировать товар', 'url' => ['/shop/item/', 'alias' => $shop->alias]],
                        ['label' => 'Редактировать информацию', 'url' => ['/shop/update', 'alias' => $shop->alias]],
                        ['label' => 'Удалить', 'url' => ['/shop/delete', 'alias' => $shop->alias], 'options' =>[
                            'class' => 'deleteShopItemMenu',
                        ]],
                    ],
                ]) ?>
            </div>
        </div>

    <?php endforeach; ?>
</div>