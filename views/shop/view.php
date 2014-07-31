<?php
use yii\helpers\Html;
use app\models\Shop;

$this->registerJsFile('@web/js/jquery/jquery.tablesorter.min.js', ['yii\web\JqueryAsset']);

$this->title = $shop->name;
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['/shop/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content page" id="<?= $shop->id ?>">
	<h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <img src="<?= $shop->getLogo() ?>" alt="<?= $shop->name ?>" class="img-rounded" />
            <div class="info">
                <p><?= $shop->about ?></p>
                <?php if($shop->subway) echo '<p>Станция метро: /go '.$shop->subway.'</p>' ?>
                <?php if(isset($shop->x_cord) && isset($shop->z_cord)) echo '<p>Координаты: X: '.$shop->x_cord.', Z: '.$shop->z_cord.'</p>' ?>
                <?= '<p>Последнее обновление: '.gmdate("Y-m-d H:i", $shop->updated_at).'</p>' ?>
                <?php if($shop->source) echo '<p><a href="http://'.$shop->source.'" target="_blank">Источник</a></p>' ?>
            </div>
        </div>
        <?php if(isset($shop->description)) echo'<div class="panel-body">'.$shop->description.'</div>' ?>
        <table class="table table-hover item-list sort">
            <thead>
                <tr>
                    <th width="5%"></th>
                    <th width="5%">ID</th>
                    <th class="name">Название</th>
                    <th width="15%">Цена продажи</th>
                    <?php /*<th width="15px" class="{sorter: false} flag"></th> */ ?>
                    <th width="15%">Цена покупки</th>
                     <?php /*<th width="15px" class="{sorter: false} flag"></th> */ ?>
                    <th width="15%">Кол-во за сделку</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($shop->prices as $price):
                //$complaint_sell = ($price->complaint_sell == 0)?' ':' bad';
                //$complaint_buy = ($price->complaint_buy == 0)?' ':' complaint bad';
            ?>
                <tr>
                    <td><img src="/images/items/<?= $price->item->alias ?>.png" alt="<?= $price->item->name; ?>" class="small-icon"></td>
                    <td><?= $price->item->alias; ?></td>
                    <td class="name"><a href="<?= Yii::$app->urlManager->createUrl(['item/view', 'alias' => $price->item->alias]) ?>"><?= $price->item->name; ?></a></td>
                    <td><?= ($price->price_sell)?$price->price_sell:'—' ?></td>
                    <?php /*<td class="flag"><span class="glyphicon glyphicon-bookmark complaint<?= $complaint_sell ?>" data-type="sell"></span></td> */ ?>
                    <td><?= ($price->price_buy)?$price->price_buy:'—' ?></td>
                    <?php /*<td class="flag"><span class="glyphicon glyphicon-bookmark complaint<?= $complaint_buy ?>" data-type="buy"></span></td>  */ ?>
                    <td><?= $price->stuck; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>