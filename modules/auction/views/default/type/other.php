<?php
/**
 * Шаблон для отображаения предмета как лота аукциона.
 * @var yii\base\View $this
 * @var app\modules\auction\models\Lot $model
 */

use yii\helpers\Html;
use yii\helpers\Json;

$data = json_decode($model->metadata, false);
?>
<div class="preview item clearfix">
    <?php if(json_last_error() === JSON_ERROR_NONE): ?>
        <img src="/images/auction/<?= $data->picture_url ?>">
    <?php else: ?>
        <p>Ошибка валидации Json</p>
    <?php endif; ?>
</div>