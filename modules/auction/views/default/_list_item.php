<?php
/**
 * Шаблон одного пользователя на странице всех записей.
 * @var yii\base\View $this
 * @var app\modules\auction\models\Lot $model
 */

use yii\helpers\Html;
use yii\helpers\Json;
use app\modules\auction\models\Lot;

?>
<div class="well clearfix">
    <div class="info pull-left">
        <h3><a href="<?= Yii::$app->urlManager->createUrl(['auction/default/view', 'id' => $model->id]) ?>"><?= Html::encode($model->name) ?></a></h3>

        <?php if($model->type_id === Lot::TYPE_ITEM): ?>

            <?= $this->render('type/item', [
                'model' => $model,
            ]) ?>

        <?php elseif($model->type_id === Lot::TYPE_ITEM_IMAGE): ?>

            <?= $this->render('type/item-image', [
                'model' => $model,
            ]) ?>

        <?php elseif($model->type_id === Lot::TYPE_LAND): ?>

            <?= $this->render('type/region-short', [
                'model' => $model,
            ]) ?>

        <?php else: ?>

            <?= $this->render('type/other', [
                'model' => $model,
            ]) ?>

        <?php endif; ?>

        <ul class="list-group">
            <li class="list-group-item btn-list">
                <div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
                    <?php if($model->time_elapsed < time()): ?>
                        <a href="#" class="btn btn-success disabled" role="button">Аукцион закрыт</a>
                        <a href="#" class="btn btn-primary disabled" role="button"><?= ($model->bid) ? \Yii::$app->formatter->asInteger($model->bid->cost).' зелени' : 'нет ставок' ?></a>
                    <?php elseif($bid = $model->bid): ?>
                        <a href="#" class="btn btn-success disabled countdown" role="button" data-time="<?= $model->time_elapsed ?>">00:00:00</a>
                        <a href="#" class="btn btn-primary disabled" role="button"><?= \Yii::$app->formatter->asInteger($bid->cost) ?> зелени</a>
                    <?php else: ?>
                        <a href="#" class="btn btn-success disabled countdown" role="button" data-time="<?= $model->time_elapsed ?>">00:00:00</a>
                        <a href="#" class="btn btn-primary disabled" role="button">нет ставок</a>
                    <?php endif; ?>
                </div>
            </li>
        </ul>
    </div>
</div>