<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Shop $model
 */

$this->title = 'Создание магазина';
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['shop/edit']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">

	<h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>