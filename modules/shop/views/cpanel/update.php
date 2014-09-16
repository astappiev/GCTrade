<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\shop\models\Shop $model
 */

$this->title = 'Обновить информацию';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['cpanel/index']];
$this->params['breadcrumbs'][] = $this->title . ' - ' . $model->name;
?>
<div class="body-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>