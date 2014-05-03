<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Shop $model
 */

$this->title = 'Редактирование информации';
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['edit']];
$this->params['breadcrumbs'][] = 'Информация - '.$model->name;
?>
<div class="body-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>