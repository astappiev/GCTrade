<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use vova07\imperavi\Widget as Imperavi;
use vova07\fileapi\Widget as FileAPI;
use app\modules\auction\models\Lot;

/**
 * @var yii\web\View $this
 * @var app\modules\auction\models\Lot $model
 * @var yii\widgets\ActiveForm $form
 */
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

    <?= $form->field($model, 'region_name', [
        'options' => ($model->isNewRecord || $model->type_id == Lot::TYPE_LAND) ? ['class' => 'form-group'] : ['class' => 'form-group', 'style' => 'display: none;'],
    ])->textInput(['placeholder' => 'Например: Sherwood или astappev_h_3']) ?>

    <?= $form->field($model, 'item_id', [
        'options' => ($model->type_id == Lot::TYPE_ITEM_IMAGE) ? ['class' => 'form-group'] : ['class' => 'form-group', 'style' => 'display: none;'],
    ])->textInput(['placeholder' => 'Например: 278 для алмазной кирки']) ?>

    <?= $form->field($model, 'picture_url', [
        'options' => (!$model->isNewRecord && ($model->type_id != Lot::TYPE_LAND && $model->type_id != Lot::TYPE_ITEM)) ? ['class' => 'form-group'] : ['class' => 'form-group', 'style' => 'display: none;'],
    ])->widget(FileAPI::className(), [
        'crop' => true,
        'jcropSettings' => [
            'aspectRatio' => null,
            'bgColor' => '#ffffff',
            'maxSize' => [568, 800],
            //'minSize' => [10, 10],
            'keySupport' => false, // Important param to hide jCrop radio button.
            //'selection' => '100%',
            //'setSelect' => [0, 0, 200, 100]
        ],
        'cropResizeMaxWidth' => 400,
        'settings' => [
            'url' => Url::to(['/auction/cpanel/picture-upload']),
        ],
    ]) ?>

    <?= $form->field($model, 'metadata', [
        'options' => (!$model->isNewRecord && $model->type_id == Lot::TYPE_ITEM) ? ['class' => 'form-group'] : ['class' => 'form-group', 'style' => 'display: none;'],
    ])->textarea(['rows' => 4]) ?>

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

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$item = \app\modules\auction\models\Lot::TYPE_ITEM;
$item_image = \app\modules\auction\models\Lot::TYPE_ITEM_IMAGE;
$land = \app\modules\auction\models\Lot::TYPE_LAND;
$project = \app\modules\auction\models\Lot::TYPE_PROJECT;
$other = \app\modules\auction\models\Lot::TYPE_OTHER;
$this->registerJs(<<<JS
    $('#lot-type_id').on('change', function() {
        var type = $(this).val();
        var region_name = $(".field-lot-region_name");
        var metadata = $(".field-lot-metadata");
        var picture_url = $(".field-lot-picture_url");
        var item_id = $(".field-lot-item_id");

        region_name.hide();
        metadata.hide();
        picture_url.hide();
        item_id.hide();

        if(type == $item) {
            metadata.show();
        } else if (type == $land) {
            region_name.show();
        } else if (type == $item_image) {
            picture_url.show();
            item_id.show();
        } else {
            picture_url.show();
        }
    });
JS
);

