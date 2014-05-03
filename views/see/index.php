<?php
use yii\helpers\Html;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
use app\models\See;

$this->registerJsFile('@web/js/spin.min.js', ['yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/jquery.spin.js', ['yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/see.gctrade.js', ['yii\web\JqueryAsset']);

$this->title = 'Наблюдение за активностью';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="permission" class="alert-danger alert fade in hidden">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <p>Что бы получать уведомления о входе игрока в игру, вы должны подтвердить права.</p>
    <p><button id="permission" type="button" class="btn btn-danger">Подтвердить</button></p>
</div>

<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">

            <?php $form = ActiveForm::begin([
                'id' => 'see-update-form',
                'options' => ['class' => 'form-inline'],
                'fieldConfig' => ['template' => '{input}'],
            ]); ?>

            <?= $form->field($model, 'login')->textInput(['placeholder' => 'Логин игрока', 'minlength' => 3,'maxlength' => 30]) ?>
            <?= $form->field($model, 'description')->textInput(['placeholder' => 'Краткое описание', 'maxlength' => 200]) ?>

            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'name' => 'update']) ?>

            <?php ActiveForm::end(); ?>

        </div>
        <?php $logins = See::find()->where(['user_id' => Yii::$app->user->id])->all();
        if(count($logins)): ?>
        <table class="table table-hover see">
            <thead>
            <tr>
                <th width="20%">Логин</th><th>Описание</th><th width="20%">Состояние</th><th width="80px"></th>
            </tr>
            </thead>
            <tbody>

                <?php foreach ($logins as $login):
                    echo '<tr id="'.$login->id.'">';
                    echo '<td>'.$login->login.'</td>';
                    echo '<td>'.$login->description.'</td>';
                    echo '<td class="status"></td>';
                    echo '<td aling="right"><button class="btn btn-xs btn-danger" type="button" id="delete">Удалить</button></td>';
                    echo '</tr>';
                endforeach; ?>

            </tbody>
        </table>
        <?php else:
            echo '<div class="panel-body"><p>Сперва добавьте игроков.</p></div>';
        endif; ?>
    </div>
</div>
