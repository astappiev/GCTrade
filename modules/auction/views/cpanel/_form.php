<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use vova07\imperavi\Widget as Imperavi;
use vova07\fileapi\Widget as FileAPI;


/**
 * @var yii\web\View $this
 * @var app\modules\auction\models\Lot $model
 * @var yii\widgets\ActiveForm $form
 */
//$this->registerJsFile('@web/js/auction.form.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="lot-form">

    <?php $form = ActiveForm::begin([
        'id' => 'lot-form',
        'options' => ['class' => 'form-horizontal'],
        //'enableAjaxValidation' => true,
        //'enableClientValidation' => false,
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-10\">{input}</div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
            'labelOptions' => ['class' => 'control-label col-md-2'],
        ],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Название лота', 'maxlength' => 255]) ?>

    <?= $form->field($model, 'type_id')->dropDownList($typeArray) ?>

    <?= $form->field($model, 'region_name')->textInput(['placeholder' => 'Например: Sherwood или astappev_h_3']) ?>

    <?= $form->field($model, 'metadata')->textarea(['readonly' => true, 'rows' => 4]) ?>

    <?= $form->field($model, 'description')->widget(Imperavi::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'pastePlainText' => true,
            'plugins' => [
                'fullscreen'
            ],
            'imageUpload' => Url::to(['/auction/image-upload'])
        ]
    ]); ?>

    <?= $form->field($model, 'price_min', [
        'template' => "{label}\n<div class=\"col-md-10\"><div class=\"input-group\">{input}<span class=\"input-group-addon\">зелени</span></div></div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
    ])->textInput(['placeholder' => 'Минимальная цена продажи', 'maxlength' => 11, 'minlength' => 1]) ?>

    <?= $form->field($model, 'price_step', [
        'template' => "{label}\n<div class=\"col-md-10\"><div class=\"input-group\">{input}<span class=\"input-group-addon\">зелени</span></div></div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
    ])->textInput(['placeholder' => 'Минимальное различение между ставками', 'maxlength' => 11, 'minlength' => 1]) ?>

    <?= $form->field($model, 'price_blitz', [
        'template' => "{label}\n<div class=\"col-md-10\"><div class=\"input-group\">{input}<span class=\"input-group-addon\">зелени</span></div></div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
    ])->textInput(['placeholder' => 'Цена за которую вы готовы моментально продать лот', 'maxlength' => 11, 'minlength' => 1]) ?>

    <?= $form->field($model, 'picture_url')->widget(FileAPI::className(), [
        'settings' => [
            'url' => Url::to(['/shop/picture-upload']),
            'imageSize' => [
                'minWidth' => 60,
                'minHeight' => 60,
                'maxWidth' => 568,
                'maxHeight' => 720,
            ],
        ],
        'crop' => true
    ]) ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
