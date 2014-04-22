<?php
use yii\helpers\Html;
use app\models\Item;

$this->registerJsFile('@web/js/jquery.filtertable.min.js', ['yii\web\JqueryAsset']);

$this->title = 'Каталог товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content item-list">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <table class="table table-hover filter pointer">
            <thead>
                <tr>
                    <th width="5%"></th>
                    <th width="7%">ID</th>
                    <th class="name">Название</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach(Item::find()->orderBy(['ABS(alias)' => SORT_ASC])->all() as $item): ?>
                <tr data-href="<?= Yii::$app->urlManager->createUrl(['item/page', 'alias' => $item->alias]) ?>">
                    <td><img src="/images/items/<?= $item->alias; ?>.png" alt="<?= $item->name; ?>" align="left" class="small-icon"></td>
                    <td><?= $item->alias; ?></td>
                    <td class="name"><?= $item->name; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>