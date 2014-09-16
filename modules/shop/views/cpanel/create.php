<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\shop\models\Shop $model
 */

$this->title = 'Создать магазин';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['cpanel/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">

	<h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>