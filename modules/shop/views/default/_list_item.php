<?php
/**
 * Шаблон одного пользователя на странице всех записей.
 * @var yii\base\View $this
 * @var $model app\modules\shop\models\Shop
 */

use yii\helpers\Html;

?>
<a href="<?= Yii::$app->urlManager->createUrl(['shop/default/view', 'alias' => $model->alias]) ?>">
    <img src="<?= $model->getLogo() ?>" alt="<?= $model->name ?>" class="img-rounded" />
</a>
<div class="info">
    <h3>
        <a href="<?= Yii::$app->urlManager->createUrl(['shop/default/view', 'alias' => $model->alias]) ?>">
            <?= Html::encode($model->name) ?>
        </a>
    </h3>
    <p><?= Html::encode($model->about) ?></p>
    <?php
    if(isset($model->subway))
        echo '<p>'.Yii::t('shop', 'Subway station:').' /go '.Html::encode($model->subway).'</p>';

    if(isset($model->x_cord) && isset($model->z_cord))
        echo '<p id="cord" data-x="'.Html::encode($model->x_cord).'" data-z="'.Html::encode($model->x_cord).'">'.Yii::t('shop', 'Coordinates:').' X: '.$model->x_cord.', Z: '.$model->z_cord.'</p>';
    ?>
    <?= '<p>'.Yii::t('shop', 'Last update:').' <span class="label label-info">'.Yii::t('shop', '{0, date, medium}', $model->updated_at).'</span></p>' ?>
</div>
