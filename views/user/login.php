<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\authclient\widgets\AuthChoice;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <p>Что бы иметь доступ к управлению, вы должны авторизоваться:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form->field($model, 'username') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
            <div style="color:#999; margin:1em 0">
                Если вы забыли пароль, вы всегда можете его <?= Html::a('восстановить', ['user/request-password-reset']) ?>.
            </div>
            <div class="form-group">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                <?php if(\Yii::$app->has('authClientCollection'))
                {
                    $authChoice = AuthChoice::begin(['baseAuthUrl' => ['user/auth']]);
                    $greencubes = $authChoice->getClients()["greencubes"];
                    $authChoice->clientLink($greencubes, 'GreenCubes Auth', ['class' => 'btn btn-greencubes auth-link']);
                    AuthChoice::end();
                }  ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
