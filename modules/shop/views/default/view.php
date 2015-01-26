<?php

use yii\helpers\Html;
use app\modules\shop\models\Shop;

/**
 * @var $this yii\web\View
 * @var $model app\modules\shop\models\Shop
 */

$this->registerJsFile('@web/js/jquery/jquery.tablesorter.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/shop', 'SHOP'), 'url' => ['/shop/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content page" id="<?= $model->id ?>">
    <div class="btn-group pull-right" style="margin-top: 25px;">
        <?= Html::button('<span class="glyphicon glyphicon-envelope"></span> Написать владельцу', ['class' => 'btn btn-warning', 'data-toggle' => 'modal', 'data-target' => '#reviewToOwner']) ?>
    </div>
	<h1><?= Html::encode($this->title) . ($model->status == Shop::STATUS_DRAFT ? ' (Черновик)' : null) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <img src="<?= $model->getLogo() ?>" alt="<?= Html::encode($model->name) ?>" class="img-rounded" />
            <div class="info">
                <p><?= Html::encode($model->about) ?></p>
                <?php if(isset($model->subway)):
                    echo '<p>'.Yii::t('app/shop', 'Subway station:').' /go '.Html::encode($model->subway).'</p>';
                endif; ?>
                <?php if(isset($model->x_cord) && isset($model->z_cord)):
                    echo '<p id="cord" data-x="'.Html::encode($model->x_cord).'" data-z="'.Html::encode($model->x_cord).'">'.Yii::t('app/shop', 'Coordinates:').' X: '.$model->x_cord.', Z: '.$model->z_cord.'</p>';
                endif; ?>
                <?= '<p>'.Yii::t('app/shop', 'Last update:').' <span class="label label-info">'.Yii::t('app/shop', '{0, date, medium}', $model->updated_at).'</span></p>' ?>
                <?php if($model->source):
                    echo '<p><a href="http://'.$model->source.'" target="_blank">'.Yii::t('app/shop', 'Source:').'</a></p>';
                endif; ?>
            </div>
        </div>
        <?php if(isset($model->description)):
            echo'<div class="panel-body">'.$model->description.'</div>';
        endif; ?>

        <?= $this->render(($model->type == Shop::TYPE_GOODS) ? 'good/view-table' : 'book/view-table', [
            'model' => $model,
        ]) ?>
    </div>

</div>

<?php \yii\bootstrap\Modal::begin([
    'id' => 'reviewToOwner',
    'header' => '<h3 class="modal-title">Сообщение владельцу</h3>',
    'footer' => '<button type="button" id="review-shop-send" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> Отправить</button>',
]); ?>
    <?php
    $message = new \app\modules\users\models\Message();
    $form = \yii\bootstrap\ActiveForm::begin([
        'id' => 'review-shop',
        'action' => ['/users/message/create'],
    ]); ?>

    <?= Html::activeHiddenInput($message, 'user_recipient', ['value' => $model->user_id]) ?>

    <?= $form->field($message, 'title')->textInput(['readonly' => true, 'value' => 'Отзыв о магазине «'.$model->name.'»']) ?>

    <?= $form->field($message, 'text')->textArea(['rows' => 8, 'placeholder' => 'Текст сообщения', 'maxlength' => 4000]) ?>

<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php \yii\bootstrap\Modal::end(); ?>
<?php
$this->registerJs('
    $("#review-shop-send").on( "click", function() {
        $("#review-shop").submit();
    });
    $("#review-shop").on("beforeSubmit.yii", function(e) {
        $.get($(this).attr("action"), $(this).serialize())
        .done(function(result) {
            $(".body-content").before("<div class=\"alert alert-success fade in\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">×</span><span class=\"sr-only\">Закрыть</span></button><strong>Спасибо!</strong> Сообщение отправлено владельцу магазина.</div>");
            $("#reviewToOwner").modal("hide");
        })
        .fail(function() {
            $(".body-content").before("<div class=\"alert alert-danger fade in\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">×</span><span class=\"sr-only\">Закрыть</span></button><strong>Возникла ошибка.</strong> Сообщите о ней разработчику.</div>");
        }
    );
    return false;
});');