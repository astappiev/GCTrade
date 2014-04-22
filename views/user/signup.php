<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\authclient\widgets\AuthChoice;

$this->registerCssFile('@web/css/font-awesome.css', ['yii\bootstrap\BootstrapAsset']);
$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, заполните поля, необходимые для регистрации:</p>

    <div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
				<?= $form->field($model, 'username') ?>
				<?= $form->field($model, 'email') ?>
				<?= $form->field($model, 'password')->passwordInput() ?>
				<div class="form-group">
					<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    <?php $authChoice = AuthChoice::begin(['baseAuthUrl' => ['user/auth']]);
                    $google = $authChoice->getClients()["google"];
                    $facebook = $authChoice->getClients()["facebook"];
                    $authChoice->clientLink($google, '<i class="fa fa-google-plus"></i>', ['class' => 'btn btn-social-icon btn-google-plus auth-link']);
                    $authChoice->clientLink($facebook, '<i class="fa fa-facebook"></i>', ['class' => 'btn btn-social-icon btn-facebook auth-link']);
                    AuthChoice::end(); ?>
				</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
