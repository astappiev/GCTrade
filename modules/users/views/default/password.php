<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Изменение пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= ($model->scenario == 'add')?'Пароль не задан, вы можете его установить.<br>':$form->field($model, 'old_password')->passwordInput(['placeholder' => 'Старый пароль'])->hint('Необходим для подтверждения изменений.');
                ?>
                <br>
				<?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Новый пароль']) ?>
                <?= $form->field($model, 'protect_password')->passwordInput(['placeholder' => 'Повтор нового пароля']) ?>
				<div class="form-group">
					<?= Html::submitButton('Изменить', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
				</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
