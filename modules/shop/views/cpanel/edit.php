<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use app\modules\shop\models\Shop;

/**
 * @var yii\web\View $this
 * @var app\modules\shop\models\Shop $model
 */

$this->registerJsFile(YII_ENV_PROD ? '@web/js/jquery/jquery.spin.min.js' : '@web/js/jquery/jquery.spin.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(YII_ENV_PROD ? '@web/js/shop.gctrade.min.js' : '@web/js/shop.gctrade.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = 'Редактор ассортимента (' . $model->getType() . ')';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['cpanel/index']];
$this->params['breadcrumbs'][] = $this->title . ' - ' . $model->name;
?>
<div class="body-content edit-shop" id="<?= $model->id ?>">
	<h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <div class="pull-left">
                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Добавить', '#', ['class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => '#item-modal']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-exclamation-sign"></span> Удалить все', ['cpanel/item-clear', 'shop_id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы действительно хотите весь ассортимент?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <div class="pull-right">
                <?= Html::a('<span class="glyphicon glyphicon-import"></span> Импорт', '#', ['class' => 'btn btn-warning', 'data-toggle' => 'modal', 'data-target' => '#import-modal']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-export"></span> Экспорт', ['cpanel/export', 'alias' => $model->alias], ['class' => 'btn btn-info']) ?>
            </div>
        </div>
        <?php if(empty($model->products)): ?>
            <div class="panel-body text-center" id="empty-items">У данного магазина, нет товара.</div>
        <?php else: ?>
            <?= $this->render(($model->type == Shop::TYPE_GOODS) ? 'good/edit-table' : 'book/edit-table', [
                'model' => $model,
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->render(($model->type == Shop::TYPE_GOODS) ? 'good/edit-modal' : 'book/edit-modal', [
    'model' => $model,
]) ?>