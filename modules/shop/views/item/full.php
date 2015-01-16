<?php

use yii\helpers\Html;
use app\modules\shop\models\Item;

/**
 * @var $this yii\web\View
 */

$this->registerJsFile('@web/js/jquery/jquery.filtertable.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = 'Каталог товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content item-list">

    <div class="btn-group pull-right" style="margin-top: 25px;">
        <a href="<?= Yii::$app->urlManager->createUrl(['/shop/item/index']) ?>" class="btn btn-success" role="button">Только в наличии</a>
        <a class="btn btn-primary active" role="button">Все товары</a>
    </div>

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
            <?php foreach(Item::find()->orderBy(['id_primary' => SORT_ASC, 'id_meta' => SORT_ASC])->all() as $item): ?>
                <tr>
                    <td><img src="/images/items/<?= $item->getAlias(); ?>.png" alt="<?= $item->name; ?>" align="left" class="small-icon" /></td>
                    <td class="td-filter"><?= $item->getAlias(); ?></td>
                    <td class="td-filter name"><a href="<?= Yii::$app->urlManager->createUrl(['shop/item/view', 'alias' => $item->alias]) ?>"><?= $item->name; ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>