<?php
use yii\helpers\Html;

$resetLink = \Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->password_reset_token]);
?>

<p>Привет <?= Html::encode($user->username) ?>, для восстановления пароля воспользуйся ссылкой:</p>
<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
<p>Если ты не понимаешь как здесь оказалось это письмо, просто проигнорируй его.</p>