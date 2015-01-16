<?php

/**
 * Шаблон одного пользователя на странице всех записей.
 * @var yii\base\View $this
 * @var app\modules\shop\models\Shop $model
 */

use yii\helpers\Html;
?>
<div class="well clearfix">
    <a href="<?= Yii::$app->urlManager->createUrl(['shop/default/view', 'alias' => $model->alias]) ?>">
        <img src="<?= $model->logo ?>" alt="<?= $model->name ?>" class="img-rounded" />
    </a>
    <div class="info">
        <h3><a href="<?= Yii::$app->urlManager->createUrl(['shop/default/view', 'alias' => $model->alias]) ?>"><?= Html::encode($model->name) ?></a></h3>
        <p><?= $model->about ?></p>
        <?= '<p>Последнее обновление: '.gmdate("Y-m-d H:i:s", $model->updated_at).'</p>' ?>
        <?= Html::a('Редактировать товар', ['edit', 'alias' => $model->alias], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Редактировать информацию', ['update', 'alias' => $model->alias], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Удалить', ['delete', 'alias' => $model->alias], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить магазин?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
</div>