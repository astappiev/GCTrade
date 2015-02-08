<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\users\models\User $model
 * @var yii\widgets\ActiveForm $form
 */

$this->title = Yii::t('users', 'MESSAGE_VIEWS_EDIT_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin([
				'id' => 'form-change-email',
				'options' => ['class' => 'form-horizontal'],
				'fieldConfig' => [
					'checkboxTemplate' => "<div class=\"col-md-offset-2 col-md-10\"><div class=\"checkbox\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div></div>",
					'template' => "{label}\n<div class=\"col-md-10\">{input}</div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
					'labelOptions' => ['class' => 'control-label col-md-2'],
				],
			]); ?>
				<?= $form->field($model, 'email')->textInput() ?>
                <?= $form->field($model->setting, 'mail_delivery')->checkbox() ?>
                <?= $form->field($model->setting, 'mail_see_leave')->checkbox() ?>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
					</div>
				</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
