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

        <div class="lot-preview clearfix">
            <?php if ($model->type_id === Lot::TYPE_ITEM) {
                echo \app\modules\auction\widgets\ViewItem::widget(['metadata' => $model->metadata]);
            } elseif ($model->type_id === Lot::TYPE_LAND) {
                echo \app\modules\auction\widgets\ViewRegion::widget(['metadata' => $model->metadata, 'short' => true]);
            } else {
                echo $this->render('type/other', [
                    'model' => $model,
                ]);
            } ?>
        </div>

        <ul class="list-group">
            <li class="list-group-item btn-list">
                <div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
                    <?php if($model->getCurrentStatus() === Lot::STATUS_CLOSED || $model->getCurrentStatus() === Lot::STATUS_FINISHED): ?>
                        <a href="#" class="btn btn-success disabled" role="button">Аукцион закрыт</a>
                        <a href="#" class="btn btn-primary disabled" role="button"><?= ($model->bid) ? \Yii::$app->formatter->asInteger($model->bid->cost).' зелени' : 'нет ставок' ?></a>
                    <?php elseif($model->getCurrentStatus() === Lot::STATUS_STARTED): ?>
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