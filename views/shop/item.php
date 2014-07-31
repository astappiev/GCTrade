<?php
use yii\helpers\Html;
use app\models\Shop;
use yii\bootstrap\Modal;
use app\models\Price;

$this->registerJsFile('@web/js/jquery/jquery.spin.min.js', ['yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/item.gctrade.min.js', ['yii\web\JqueryAsset']);

$shop = Shop::find()->where(['alias' => $url])->one();

$this->title = 'Управление '.$shop->name;
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['edit']];
$this->params['breadcrumbs'][] = 'Товар - '.$shop->name;
?>
<div class="body-content edit-shop" id="<?= $shop->id ?>">
	<h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <button class="btn btn-warning" data-toggle="modal" data-target="#ImportFileModal" role="button"><span class="glyphicon glyphicon-import"></span> Импорт</button>
            <a class="btn btn-info" href="<?= Yii::$app->urlManager->createUrl(['shop/export', 'id' => $shop->id]) ?>" role="button"><span class="glyphicon glyphicon-export"></span> Экспорт</a>
            <button class="btn btn-primary" data-toggle="modal" data-target="#AddModal" role="button"><span class="glyphicon glyphicon-plus"></span> Добавить товар</button>
            <a class="btn btn-danger" href="<?= Yii::$app->urlManager->createUrl(['shop/clearitem', 'id_shop' => $shop->id]) ?>" role="button"><span class="glyphicon glyphicon-exclamation-sign"></span> Удалить все</a>
            <?php /*<a class="btn btn-success" href="<?= Yii::$app->urlManager->createUrl(['shop/complaint', 'id_shop' => $shop->id]) ?>" role="button"><span class="glyphicon glyphicon-stats"></span> Переучет</a> */ ?>
            <?php if($shop->status == 8) echo '<a class="btn btn-success" href="'.Yii::$app->urlManager->createUrl(['shop/parser/'.$shop->alias]).'" role="button"><span class="glyphicon glyphicon-download"></span> Синхронизировать</a>'; ?>
        </div>
        <?php if(isset($shop->description)) echo'<div class="panel-body"></div>' ?>
        <table class="table table-hover item-list sort not-cursor">
            <thead>
                <tr>
                    <th width="5%"></th>
                    <th width="5%">ID</th>
                    <th class="name">Название</th>
                    <th width="15%">Цена продажи</th>
                    <th width="15px" class="{sorter: false} flag"></th>
                    <th width="15%">Цена покупки</th>
                    <th width="15px" class="{sorter: false} flag"></th>
                    <th width="15%">Кол-во за сделку</th>
                    <th width="10%"></th>
                </tr>
            </thead>
            <tbody>
            <?php
            if(empty($shop->prices)) echo '<tr><td colspan="9" style="text-align: center;">В данный момент у вас нет товаров.</td></tr>';
            foreach(Price::find()->where(['id_shop' => $shop->id])->orderBy(['complaint_sell' => SORT_DESC, 'complaint_buy' => SORT_DESC, 'id_item' => SORT_ASC])->all() as $price):
                $complaint_sell = ($price->complaint_sell == 0)?' ':' bad';
                $complaint_buy = ($price->complaint_buy == 0)?' ':' complaint bad';
            ?>
                <tr>
                    <td><img src="/images/items/<?= $price->item->alias; ?>.png" alt="<?= $price->item->name; ?>" align="left" class="small-icon" /></td>
                    <td><?= $price->item->alias; ?></td>
                    <td class="name"><a href="<?= Yii::$app->urlManager->createUrl(['item/view', 'alias' => $price->item->alias]) ?>"><?= $price->item->name; ?></a></td>
                    <td><?= ($price->price_sell)?$price->price_sell:'—' ?></td>
                    <td class="flag"><span class="glyphicon glyphicon-bookmark complaint<?= $complaint_sell ?>" data-type="sell"></span></td>
                    <td><?= ($price->price_buy)?$price->price_buy:'—' ?></td>
                    <td class="flag"><span class="glyphicon glyphicon-bookmark complaint<?= $complaint_buy ?>" data-type="buy"></span></td>
                    <td><?= $price->stuck; ?></td>
                    <td class="control">
                        <div class="btn-group btn-group-sm">
                            <button id="editButtons" class="btn btn-primary" title="Редактировать"><span class="glyphicon glyphicon-pencil"></span></button>
                            <button id="removeButtons" class="btn btn-danger" title="Удалить"><span class="glyphicon glyphicon-remove"></span></button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php Modal::begin([
    'id' => 'ImportFileModal',
    'toggleButton' => null,
    'header' => '<h3 class="modal-title">Импорт товаров с файла</h3>',
    'footer' => '<button type="button" id="sync" class="btn btn-warning"><span class="glyphicon glyphicon-import"></span> Импорт</button>',
]); ?>
    <div class="form-group">
        <label for="InputFile">Выбирите CSV файл прайса</label>
        <input type="file" id="InputFile" accept=".csv, text/plain">
        <p class="help-block">Файл должен содержать в себе информацию по такому шаблону: (количество строк не ограничено)</p>
        <pre>id, цена продажи, цена покупки, количество за одну операцию;</pre>
        <p class="help-block">В случае если товар есть в базе, он бдует обновлен, если его нет - добавлен.</p>
    </div>
    <table class="table AddItem" id="ImportItemFile" style="display: none;">
        <thead>
            <tr>
                <th>Товар</th>
                <th>Цена продажи</th>
                <th>Цена покупки</th>
                <th>Кол-во</th>
                <th width="40px"></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
<?php Modal::end(); ?>

<?php Modal::begin([
    'id' => 'AddModal',
    'toggleButton' => null,
    'header' => '<h3 class="modal-title">Добавление товаров</h3>',
    'footer' => '<button type="button" id="sync" class="btn btn-warning"><span class="glyphicon glyphicon-import"></span> Импорт</button>',
]); ?>
    <form class="form-inline" role="form" id="AddItemForm">
        <div class="form-group">
            <label class="sr-only" for="InputID">ID товара</label>
            <input type="text" name="ID" class="form-control" id="InputID" placeholder="ID товара">
        </div>
        <div class="form-group">
            <label class="sr-only" for="InputBuy">Цена продажи</label>
            <input type="text" name="Buy" class="form-control" id="InputBuy" placeholder="Цена продажи">
        </div>
        <div class="form-group">
            <label class="sr-only" for="InputSell">Цена покупки</label>
            <input type="text" name="Sell" class="form-control" id="InputSell" placeholder="Цена покупки">
        </div>
        <div class="form-group">
            <label class="sr-only" for="InputStuck">Кол-во</label>
            <input type="text" name="Stuck" class="form-control" id="InputStuck" placeholder="Кол-во">
        </div>
        <button type="submit" class="btn btn-default">Добавить</button>
    </form>
    <table class="table AddItem" id="AddItemTable" style="display: none;">
        <thead><tr><th>Товар</th><th>Цена продажи</th><th>Цена покупки</th><th>Кол-во</th><th width="40px"></th></tr></thead>
        <tbody></tbody>
    </table>
<?php Modal::end(); ?>

<?php Modal::begin([
    'id' => 'EditModal',
    'toggleButton' => null,
    'header' => '<h3 class="modal-title">Редактировать товар</h3>',
    'footer' => '<button type="button" id="editButtonModal" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Изменить</button>',
]); ?>
    <form class="form-horizontal" role="form" id="EditItemForm">
        <input type="text" class="hide" id="IdHide">
        <div class="form-group">
            <label class="col-sm-4 control-label" id="name" data-id=""></label>
            <div class="col-sm-6">
                <p class="form-control-static" id="name"></p>
            </div>
        </div>
        <div class="form-group">
            <label for="Sell" class="col-sm-4 control-label">Цена продажи</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="Sell">
            </div>
        </div>
        <div class="form-group">
            <label for="Buy" class="col-sm-4 control-label">Цена покупки</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="Buy">
            </div>
        </div>
        <div class="form-group">
            <label for="Stuck" class="col-sm-4 control-label">Кол-во за сделку</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="Stuck">
            </div>
        </div>
    </form>
<?php Modal::end(); ?>