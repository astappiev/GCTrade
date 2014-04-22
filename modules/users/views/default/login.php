<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\authclient\widgets\AuthChoice;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\users\models\forms\LoginForm $model
 */
$this->registerCssFile('@web/css/font-awesome.css', ['yii\bootstrap\BootstrapAsset']);
$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <p>Что бы иметь доступ к персональным функциям, вы должны авторизоваться:</p>

	<?php $form = ActiveForm::begin([
		'id' => 'login-form',
		'options' => ['class' => 'form-horizontal'],
		'fieldConfig' => [
			'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
			'labelOptions' => ['class' => 'col-lg-2 control-label'],
		],

	]); ?>

	<?= $form->field($model, 'username') ?>
	<?= $form->field($model, 'password')->passwordInput() ?>
	<?= $form->field($model, 'rememberMe', [
		'template' => "{label}<div class=\"col-lg-offset-2 col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
	])->checkbox() ?>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                <?php $authChoice = AuthChoice::begin(['baseAuthUrl' => ['user/auth']]);
                $google = $authChoice->getClients()["google"];
                $facebook = $authChoice->getClients()["facebook"];
                $authChoice->clientLink($google, '<i class="fa fa-google-plus"></i>', ['class' => 'btn btn-social-icon btn-google-plus auth-link']);
                $authChoice->clientLink($facebook, '<i class="fa fa-facebook"></i>', ['class' => 'btn btn-social-icon btn-facebook auth-link']);
                AuthChoice::end(); ?>

                <br/><br/>
                <?= Html::a("Регистрация", ["/user/signup"]) ?> /
                <?= Html::a("Забыли пароль?", ["/user/forgot"]) ?> /
                <?= Html::a("Отправить подтверждение повторно", ["/user/resend"]) ?>
		</div>
	</div>

	<?php ActiveForm::end(); ?>

</div>
