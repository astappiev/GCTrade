<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\extensions\tinymce\Tinymce;
use app\extensions\fileapi\FileAPIAdvanced;

/**
 * @var yii\web\View $this
 * @var app\models\Shop $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="shop-form">

    <?php $form = ActiveForm::begin([
        'id' => 'shop-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-10\">{input}</div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
            'labelOptions' => ['class' => 'control-label col-md-2'],
        ],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Название магазина', 'minlength' => 3,'maxlength' => 90]) ?>

    <?= $form->field($model, 'alias')->textInput(['placeholder' => 'Краткое название на латинице', 'minlength' => 3,'maxlength' => 30]) ?>

    <?= $form->field($model, 'about')->textArea(['rows' => 2, 'placeholder' => 'Небольшое описание, для отображения в списке', 'maxlength' => 200]) ?>

    <?= $form->field($model, 'description')->widget(Tinymce::className()); ?>

    <?= $form->field($model, 'subway', [
        'template' => "{label}\n<div class=\"col-md-10\"><div class=\"input-group\"><span class=\"input-group-addon\">/go</span>{input}</div></div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
    ])->textInput(['placeholder' => 'Название станции', 'maxlength' => 50]) ?>

    <div class="form-group field-shop-cord">
        <label class="control-label col-md-2" for="shop-cord">Координаты</label>
        <?= $form->field($model, 'x_cord', [
            'template' => "<div class=\"col-md-10\"><div class=\"input-group\"><span class=\"input-group-addon\">X</span>{input}</div></div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
            'options' => ['class' => null],
        ])->textInput(['placeholder' => 'Координата по X', 'maxlength' => 6]) ?>
        <?= $form->field($model, 'z_cord', [
            'template' => "<div class=\"col-md-offset-2 col-md-10\"><div class=\"input-group\"><span class=\"input-group-addon\">Z</span>{input}</div></div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
            'options' => ['class' => null],
        ])->textInput(['placeholder' => 'Координата по Z', 'maxlength' => 6]) ?>
    </div>

    <?= $form->field($model, 'logo_url')->widget(FileAPIAdvanced::className(), [
        'url' => '/images/shop/',
        'deleteUrl' => Url::toRoute('delete-logo'),
        'deleteTempUrl' => Url::toRoute('deleteTempLogo'),
        'crop' => true,
        'cropResizeWidth' => 150,
        'cropResizeHeight' => 150,
        'settings' => [
            'url' => Url::toRoute('uploadTempLogo'),
        ]
    ]) ?>

    <?= $form->field($model, 'source')->textInput(['placeholder' => 'В том случае есть существует независимый прайс, укажите его адрес', 'maxlength' => 90]) ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
