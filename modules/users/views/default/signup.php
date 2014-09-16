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
