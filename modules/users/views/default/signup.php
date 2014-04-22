<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\authclient\widgets\AuthChoice;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\users\models\User $user
 * @var app\modules\users\models\User $profile
 * @var string $userDisplayName
 */
$this->registerCssFile('@web/css/font-awesome.css', ['yii\bootstrap\BootstrapAsset']);
$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <?php if ($flash = Yii::$app->session->getFlash("Register-success")): ?>

        <div class="alert alert-success">
            <p><?= $flash ?></p>
        </div>

    <?php else: ?>

        <p>Пожалуйста, заполните поля, необходимые для регистрации:</p>

        <?php $form = ActiveForm::begin([
            'id' => 'signup-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-2 control-label'],
            ],
            'enableAjaxValidation' => true,
        ]); ?>

        <?php if (\Yii::$app->getModule("user")->requireUsername): ?>
            <?= $form->field($user, 'username') ?>
        <?php endif; ?>

        <?php if (\Yii::$app->getModule("user")->requireEmail): ?>
            <?= $form->field($user, 'email') ?>
        <?php endif; ?>

        <?= $form->field($user, 'newPassword')->passwordInput() ?>

        <?php /* uncomment if you want to add profile fields here
        <?= $form->field($profile, 'full_name') ?>
        */ ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                <?php $authChoice = AuthChoice::begin(['baseAuthUrl' => ['user/auth']]);
                $google = $authChoice->getClients()["google"];
                $facebook = $authChoice->getClients()["facebook"];
                $authChoice->clientLink($google, '<i class="fa fa-google-plus"></i>', ['class' => 'btn btn-social-icon btn-google-plus auth-link']);
                $authChoice->clientLink($facebook, '<i class="fa fa-facebook"></i>', ['class' => 'btn btn-social-icon btn-facebook auth-link']);
                AuthChoice::end(); ?>
                или <?= Html::a("Войти", ["/user/login"]) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    <?php endif; ?>

</div>
