<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Изменить профиль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
				<?= $form->field($model, 'email')->textInput() ?>
                <br>
                <?= $form->field($model, 'delivery')->checkbox() ?>

				<div class="form-group">
					<?= Html::submitButton('Изменить', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
				</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
