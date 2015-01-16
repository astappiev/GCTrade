<?php
/**
 * Шаблон для отображаения предмета как лота аукциона.
 * @var yii\base\View $this
 * @var app\modules\auction\models\Lot $model
 */

use yii\helpers\Html;
use yii\helpers\Json;

$data = Json::decode($model->metadata, FALSE);
?>
<div class="preview item clearfix">
    <div class="grid-table">
        <div class="grid-table-border clearfix">
            <div class="grid-table-item">
                <div class="grid-table-item-border">
                    <a class="tip">
                        <img title="<?= Html::encode($model->name) ?>" src="/images/items/<?= $data->item_id ?>.png">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="item-desc">
        <div class="arrow"></div>
        <div class="item-desc-border">
            <div class="item-desc-block">
                <img src="/images/auction/<?= $data->picture_url ?>">
            </div>
        </div>
    </div>
</div>