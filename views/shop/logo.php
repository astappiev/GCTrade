<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактировать логотип';
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['/shop/index']];
$this->params['breadcrumbs'][] = ['label' => 'Редактировать', 'url' => ['/shop/edit']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content shop-page">
	<h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <img src="/images/shop/<?= ($model->logo)?$model->logo:'nologo.png' ?>" alt="<?= $model->name ?>" class="img-rounded">
            <div class="info">
                <?php $form = ActiveForm::begin(['options' => ['class' => 'form', 'enctype' => 'multipart/form-data']]); ?>
                <?= $form->field($model, 'logo')->fileInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

</div>