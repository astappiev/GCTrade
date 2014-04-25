<?php
use yii\helpers\Html;
use app\models\Shop;
use app\models\User;

$this->title = 'Мой профиль';
$this->params['breadcrumbs'][] = $this->title;

$user = User::findOne(\Yii::$app->user->id);
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <p>Ваш логин: <?= $user->username ?></p>
    <p>Ваш email: <?= $user->email ?></p>
    <p>Дата регистрации: <?= $user->created_at ?></p>
    <p>Аккаунт GCTrade <?= ($user->status == 10)?'соединен':'не соединен' ?> с аккаунтом GreenCubes.</p>

    <?php $count_shop = Shop::find()->where('owner=:id', [':id' => Yii::$app->user->id])->count();
    if($count_shop == 0) echo 'У вас еще нет магазинов, а как следствие и товаров.';
    else
    {
        echo 'У вас '.$count_shop.' '.(($count_shop == 1)?'магазин':'магазина(ов)').'.';
    }
    ?>
</div>
