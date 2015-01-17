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
    <img src="/images/auction/<?= $data->picture_url ?>">
</div>