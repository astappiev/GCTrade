<?php
use yii\helpers\Html;
use app\models\Shop;
use app\models\User;

$this->title = 'Твой профиль';
$this->params['breadcrumbs'][] = $this->title;

$user = User::findOne(\Yii::$app->user->id);
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>
    <dl class="dl-horizontal">
        <dt>Ваш логин:</dt><dd><?= Html::encode($user->username) ?></dd>
        <dt>Ваш email:</dt><dd><?= Html::encode($user->email) ?></dd>
        <dt>Дата регистрации:</dt><dd><?= Html::encode($user->created_at) ?></dd>
        <dt>Магазинов создано:</dt><dd><?= Shop::find()->where('owner=:id', [':id' => Yii::$app->user->id])->count() ?></dd>
    </dl>
</div>
