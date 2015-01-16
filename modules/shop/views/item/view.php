<?php

use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model app\modules\shop\models\Item
 */

$this->registerJsFile('@web/js/jquery/jquery.tablesorter.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/shop', 'ITEM'), 'url' => ['/shop/item/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content page">
	<h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">

            <img src="/images/items/<?= $model->getAlias() ?>.png" alt="<?= $model->name ?>" class="img-rounded" />
            <div class="info">
                <p>ID: <?= $model->getAlias() ?></p>
                <p>Название: <?= $model->name ?></p>
            </div>

        </div>
        <table class="table table-hover sort pointer">

            <thead>
                <tr>
                    <th width="5%"></th>
                    <th class="name">Название</th>
                    <th width="15%">Цена продажи за единицу товара</th>
                    <th width="10%">Цена продажи</th>
                    <th width="15%">Цена покупки за единицу товара</th>
                    <th width="10%">Цена покупки</th>
                    <th width="10%">Кол-во за сделку</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach($model->products as $price): ?>

                    <tr>
                        <td><img src="<?= $price->shop->getLogo() ?>" alt="<?= $price->shop->name; ?>" align="left" class="small-icon img-rounded" /></td>
                        <td class="name"><a href="<?= Yii::$app->urlManager->createUrl(['shop/default/view', 'alias' => $price->shop->alias]) ?>"><?= $price->shop->name; ?></a></td>
                        <td><?= (isset($price->price_sell))?round($price->price_sell/$price->stuck, 2):'—' ?></td>
                        <td><?= (isset($price->price_sell))?$price->price_sell:'—' ?></td>
                        <td><?= (isset($price->price_buy))?round($price->price_buy/$price->stuck, 2):'—' ?></td>
                        <td><?= (isset($price->price_buy))?$price->price_buy:'—' ?></td>
                        <td><?= $price->stuck; ?></td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>
    </div>

</div>