<?php

use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model app\modules\auction\models\Lot
 */

$this->title = 'Обновление лота: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Аукцион', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['default/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="body-content auction cpanel lot-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'typeArray' => $typeArray,
        'statusArray' => $statusArray,
    ]) ?>

</div>
