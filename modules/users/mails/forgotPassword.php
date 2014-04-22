<?php

use app\modules\users\models\User;
use app\modules\users\models\Userkey;

/**
 * @var string $subject
 * @var app\modules\users\models\User $user
 * @var app\modules\users\models\Userkey $userkey
 */
?>

<h3><?= $subject ?></h3>

<p>Please use this link to reset your password:</p>

<p><?= Yii::$app->urlManager->createAbsoluteUrl(["user/reset", "key" => $userkey->key]); ?></p>