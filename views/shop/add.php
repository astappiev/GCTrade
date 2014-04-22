<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Добавление магазина';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin([
            'id' => 'AddShop-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-md-10\">{input}</div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
                'labelOptions' => ['class' => 'control-label col-md-2'],
            ],
        ]); ?>
        <?= $form->field($model, 'name')->textInput(['placeholder' => 'Название магазина', 'minlength' => 3,'maxlength' => 90]) ?>
        <?= $form->field($model, 'alias')->textInput(['placeholder' => 'Краткое название на латинице', 'minlength' => 3,'maxlength' => 30]) ?>
        <?= $form->field($model, 'about')->textArea(['rows' => 2, 'placeholder' => 'Небольшое описание, для отображения в списке', 'maxlength' => 200]) ?>
        <?= $form->field($model, 'description')->textArea(['rows' => 6, 'placeholder' => 'Расширенное описание']) ?>
        <?= $form->field($model, 'subway', [
            'template' => "{label}\n<div class=\"col-md-10\"><div class=\"input-group\"><span class=\"input-group-addon\">/go</span>{input}</div></div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
        ])->textInput(['placeholder' => 'Название станции', 'maxlength' => 50]) ?>

        <div class="form-group field-shop-cord">
            <label class="control-label col-md-2" for="shop-cord">Координаты</label>
            <div class="col-md-10 row">
                <div class="col-xs-2">
                    <?= $form->field($model, 'x_cord', [
                        'template' => "{input}",
                        'options' => ['class' => null],
                    ])->textInput(['placeholder' => 'X', 'maxlength' => 6])   ?>
                </div>
                <div class="col-xs-2">
                    <?= $form->field($model, 'z_cord', [
                        'template' => "{input}",
                        'options' => ['class' => null],
                    ])->textInput(['placeholder' => 'Z', 'maxlength' => 6])   ?>
                </div>
            </div>
            <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
        </div>

        <?= $form->field($model, 'source')->textInput(['placeholder' => 'В том случае есть существует независимый прайс, укажите его адрес', 'maxlength' => 90]) ?>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <?= Html::submitButton('Создать', ['class' => 'btn btn-primary', 'name' => 'AddShop-button']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
</div>