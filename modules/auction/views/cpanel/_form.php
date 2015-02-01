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
 * @var array $statusArray
 * @var array $typeArray
 */
$this->registerJsFile('@web/js/jquery/jquery.typeahead.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
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
    ]);

    if($model->isNewRecord) {
        $model->scenario = "create";
        $model->type_id = Lot::TYPE_ITEM;
        $model->time_bid = 86400;
        $model->time_elapsed = 691200;
    } else {
        $model->scenario = "update";
    }?>

    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Название лота', 'maxlength' => 255, 'disabled' => !$model->getIsEditable()]) ?>

    <?= $form->field($model, 'type_id')->dropDownList($typeArray, ['disabled' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'status')->dropDownList($statusArray, ['disabled' => !$model->getIsEditable()]) ?>

    <div class="panel-box lot-details">

        <?= $form->field($model, 'region_name', [
            'options' => ($model->type_id == Lot::TYPE_LAND) ? ['class' => 'form-group'] : ['class' => 'form-group', 'style' => 'display: none;'],
        ])->textInput(['placeholder' => 'Например: Sherwood или astappev_h_3']) ?>

        <?= $form->field($model, 'item_id', [
            'options' => ($model->type_id == Lot::TYPE_ITEM) ? ['class' => 'form-group'] : ['class' => 'form-group', 'style' => 'display: none;'],
            'template' => "{label}\n<div class=\"col-md-10\"><div class=\"input-group\"><div class=\"input-group-addon item-th\"><img id=\"item_img\" class='item-th' src=\"/images/items/278.png\"/></div>{input}</div></div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
        ])->textInput(['placeholder' => 'Алмазная кирка (278)']) ?>

        <?= $form->field($model, 'picture_url', [
            'options' => ($model->type_id !== Lot::TYPE_LAND) ? ['class' => 'form-group'] : ['class' => 'form-group', 'style' => 'display: none;'],
        ])->widget(FileAPI::className(), [
            'crop' => true,
            'jcropSettings' => [
                'aspectRatio' => null,
                'bgColor' => '#ffffff',
                'maxSize' => [568, 800],
                'keySupport' => false,
            ],
            'cropResizeMaxWidth' => 1140,
            'settings' => [
                'url' => Url::to(['/auction/cpanel/picture-upload']),
            ],
        ]) ?>

        <?= $form->field($model, 'metadata', [
            'options' => ($model->type_id == Lot::TYPE_ITEM) ? ['class' => 'form-group'] : ['class' => 'form-group', 'style' => 'display: none;'],
            'template' => "<div class=\"col-md-offset-2 col-md-10\"><p>Вместо изображения, вы можете самостоятельно ввести характеристики предмета. Описание и инструкция.</p></div>{label}\n<div class=\"col-md-10\">{input}</div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
        ])->textarea(['rows' => 4]) ?>

    </div>

    <?= $form->field($model, 'description')->widget(Imperavi::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'pastePlainText' => true,
            'plugins' => [
                'fullscreen'
            ],
            'imageUpload' => Url::to(['/auction/cpanel/image-upload'])
        ]
    ]); ?>

    <?= $form->field($model, 'time_bid')->dropDownList([
        '21600' => '6 часов',
        '43200' => '12 часов',
        '86400' => '1 день',
        '172800' => '2 дня',
        '345600' => '4 дня',
    ], ['disabled' => !$model->getIsEditable()]) ?>

    <?php if ($model->isNewRecord || $model->status == Lot::STATUS_CLOSED): ?>
        <?= $form->field($model, 'time_elapsed')->dropDownList([
            '172800' => '2 дня',
            '345600' => '1 неделя',
            '691200' => '2 недели',
            '1382400' => '4 недели',
        ]) ?>
    <?php else: ?>
        <?= $form->field($model, 'time_elapsed')->textInput(['disabled' => true, 'value' => Yii::$app->formatter->asDatetime($model->time_elapsed)])->label('Аукцион закончится') ?>
    <?php endif; ?>

    <?= $form->field($model, 'price_min', [
        'template' => "{label}\n<div class=\"col-md-10\"><div class=\"input-group\">{input}<span class=\"input-group-addon\">зелени</span></div></div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
    ])->textInput(['placeholder' => 'Минимальная цена продажи', 'maxlength' => 8, 'minlength' => 1, 'disabled' => !$model->getIsEditable()]) ?>

    <?= $form->field($model, 'price_step', [
        'template' => "{label}\n<div class=\"col-md-10\"><div class=\"input-group\">{input}<span class=\"input-group-addon\">зелени</span></div></div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
    ])->textInput(['placeholder' => 'Минимальное различение между ставками', 'maxlength' => 6, 'minlength' => 1, 'disabled' => !$model->getIsEditable()]) ?>

    <?= $form->field($model, 'price_blitz', [
        'template' => "{label}\n<div class=\"col-md-10\"><div class=\"input-group\">{input}<span class=\"input-group-addon\">зелени</span></div></div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
    ])->textInput(['placeholder' => 'Цена за которую вы готовы моментально продать лот', 'maxlength' => 10, 'minlength' => 1, 'disabled' => !$model->getIsEditable()]) ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$item = \app\modules\auction\models\Lot::TYPE_ITEM;
$land = \app\modules\auction\models\Lot::TYPE_LAND;
$this->registerJs(<<<JS
    var type_p = $model->type_id;
    var meta_land, meta_item, meta_pic;

    var meta = $('#lot-metadata');
    var region_name = $(".field-lot-region_name");
    var picture_url = $(".field-lot-picture_url");
    var item_id = $(".field-lot-item_id");
    var metadata = $(".field-lot-metadata");

    $('#lot-type_id').on('change', function() {
        var type = $(this).val();
        region_name.hide();
        picture_url.hide();
        item_id.hide();
        metadata.hide();

        if (type_p == $item) meta_item = meta.val();
        else if (type_p == $land) meta_land = meta.val();
        else meta_pic = meta.val();

        if (type == $item) {
            item_id.show();
            picture_url.show();
            metadata.show();
            meta.val(meta_item);
        } else if (type == $land) {
            region_name.show();
            meta.val(meta_land);
        } else {
            picture_url.show();
            meta.val(meta_pic);
        }

        type_p = type;
    });

    $("input#lot-item_id").typeahead({
        ajax: '/api/nomenclature'
    }).on('change', function() {
        $('#item_img').attr('src', '/images/items/' + $(this).val() + '.png');
    })
JS
);

