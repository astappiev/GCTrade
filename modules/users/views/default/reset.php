<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\users\models\forms\ResetForm $model
 * @var bool $success
 * @var bool $invalidKey
 */
$this->title = 'Новый пароль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <?php if (!empty($success)): ?>

        <div class="alert alert-success">

            <p>Password reset</p>
            <p><?= Html::a("Log in here", ["/user/login"]) ?></p>

        </div>

    <?php elseif (!empty($invalidKey)): ?>

        <div class="alert alert-danger">
            <p>Invalid key</p>
        </div>

	<?php else: ?>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'reset-form']); ?>

                    <p>Пожалуйста введите новый пароль:</p>

                    <?= $form->field($model, 'newPassword')->passwordInput() ?>
                    <?= $form->field($model, 'newPasswordConfirm')->passwordInput() ?>
                    <div class="form-group">
                        <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

	<?php endif; ?>
</div>
