<?php
use yii\helpers\Html;

$this->registerJsFile('@web/js/jquery.tablesorter.min.js', ['yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/jquery.filtertable.min.js', ['yii\web\JqueryAsset']);

$this->title = 'Каталог товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content item-list">
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
                ->select('tg_item.alias, tg_item.name, avg(price_sell/stuck) as sell, avg(price_buy/stuck) as buy')
                ->from('tg_item')
                ->leftJoin('tg_price', 'tg_item.id = tg_price.id_item')
                ->groupBy('tg_item.alias, tg_item.name')
                ->where('not price_sell is null')
                ->orderBy('ABS(alias)', SORT_ASC);

            foreach($query->each() as $item): ?>
                <tr data-href="<?= Yii::$app->urlManager->createUrl(['item/view', 'alias' => $item["alias"]]) ?>">
                    <td><img src="/images/items/<?= $item["alias"]; ?>.png" alt="<?= $item["name"]; ?>" align="left" class="small-icon"></td>
                    <td><?= $item["alias"]; ?></td>
                    <td class="name"><?= $item["name"]; ?></td>
                    <td><?= ($item["sell"])?round($item["sell"], 2):'—' ?></td>
                    <td><?= ($item["buy"])?round($item["buy"], 2):'—' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>