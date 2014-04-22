<?php
use app\modules\users\models\User;
use app\modules\users\models\Profile;
use app\modules\users\models\Userkey;

/**
 * @var string $subject
 * @var app\modules\users\models\User $user
 * @var app\modules\users\models\Profile $profile
 * @var app\modules\users\models\Userkey $userkey
 */

?>

<h3><?= $subject ?></h3>

<p>Please confirm your email address by clicking the link below:</p>

<p><?= Yii::$app->urlManager->createAbsoluteUrl(["user/confirm", "key" => $userkey->key]); ?></p>