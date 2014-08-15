<?php
use yii\helpers\Html;


if($exception->statusCode === 403) {
    $name = \Yii::t('app/error', 'Forbidden');
}
else if($exception->statusCode === 404) {
    $name = \Yii::t('app/error', 'Not Found');
    $message = \Yii::t('app/error', 'Unable to resolve the request');
}

$this->title = $name;
?>
<div class="site-index">
    <div class="jumbotron">
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="lead"><?= nl2br(Html::encode($message)) ?></p>
    </div>
</div>
