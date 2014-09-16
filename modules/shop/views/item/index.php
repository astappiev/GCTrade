<?php

use yii\helpers\Html;

$this->registerJsFile('@web/js/jquery/jquery.tablesorter.min.js', ['yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/jquery/jquery.filtertable.min.js', ['yii\web\JqueryAsset']);

/**
 * @var $this yii\web\View
 */

$this->title = Yii::t('app/shop', 'CATALOG_ITEM');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content item-list">

    <div class="btn-group pull-right" style="margin-top: 25px;">
        <a class="btn btn-success active" role="button">Только в наличии</a>
        <a href="<?= Yii::$app->urlManager->createUrl(['/shop/item/full']) ?>" class="btn btn-primary" role="button">Все товары</a>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <table class="table table-hover sort filter pointer">
            <thead>
            <tr>
                <th width="5%"></th>
                <th width="5%">ID</th>
                <th class="name">Название</th>
                <th>Средняя цена продажи за единицу</th>
                <th>Средняя цена покупки за единицу</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $query = (new \yii\db\Query())
                ->select('tg_item.id_primary, tg_item.id_meta, tg_item.name, avg(price_sell/stuck) as sell, avg(price_buy/stuck) as buy')
                ->from('tg_item')
                ->leftJoin('tg_price', 'tg_item.id = tg_price.id_item')
                ->groupBy('tg_item.id_primary, tg_item.id_meta, tg_item.name')
                ->where('not price_sell is null')
                ->orderBy('tg_item.id_primary ASC, tg_item.id_meta ASC');

            foreach($query->each() as $item): ?>

                <tr>
                    <td><img src="/images/items/<?= ($item["id_meta"] != 0) ? ($item["id_primary"].'.'.$item["id_meta"]) : $item["id_primary"]; ?>.png" alt="<?= $item["name"]; ?>" align="left" class="small-icon" /></td>
                    <td><?= $item["id_primary"].'.'.$item["id_meta"]; ?></td>
                    <td class="name"><a href="<?= Yii::$app->urlManager->createUrl(['/shop/item/view', 'alias' => ($item["id_meta"] != 0) ? ($item["id_primary"].'.'.$item["id_meta"]) : $item["id_primary"]]) ?>"><?= $item["name"]; ?></a></td>
                    <td><?= ($item["sell"])?round($item["sell"], 2):'—' ?></td>
                    <td><?= ($item["buy"])?round($item["buy"], 2):'—' ?></td>
                </tr>

            <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>