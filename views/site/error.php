<?php
use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-index">
    <div class="jumbotron">
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="lead"><?= nl2br(Html::encode($message)) ?></p>
    </div>
</div>
