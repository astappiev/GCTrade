<?php
use yii\helpers\Html;
use app\widgets\ActiveForm;
use app\models\See;
use yii\bootstrap\Alert;

$this->registerJsFile('@web/js/spin.min.js', ['yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/jquery.spin.js', ['yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/see.gctrade.js', ['yii\web\JqueryAsset']);

$this->title = 'Наблюдение за активностью';
$this->params['breadcrumbs'][] = $this->title;

Alert::begin([
    'options' => [
        'id' => 'permission',
        'class' => 'alert-danger',
    ],
]);
echo '<p>Что бы получать уведомления о входе игрока в игру, вы должны подтвердить права.</p>';
echo '<p><button id="permission" type="button" class="btn btn-danger">Подтвердить</button></p>';
Alert::end();
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">

            <?php $form = ActiveForm::begin(['id' => 'see-update-form', 'type' => ActiveForm::TYPE_INLINE]); ?>
            <?= $form->field($model, 'login') ?>
            <?= $form->field($model, 'description') ?>
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'name' => 'update']) ?>
            <?php ActiveForm::end(); ?>

        </div>
        <?php $logins = See::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
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
