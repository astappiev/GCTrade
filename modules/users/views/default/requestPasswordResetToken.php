<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста введите ваш email. Ссылка для восстановления пароля будет отправлена на ваш email.</p>

    <div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
				<?= $form->field($model, 'email') ?>
				<div class="form-group">
					<?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
				</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
