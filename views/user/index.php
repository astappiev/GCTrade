<?php
use yii\helpers\Html;
use app\models\Shop;

$this->title = 'Мой профиль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <p>Ваш логин: <?= Yii::$app->user->identity->username ?></p>
    <p>Ваш email: <?= Yii::$app->user->identity->email ?></p>
    <?php $count_shop = Shop::find()->where('owner=:id', [':id' => Yii::$app->user->identity->id])->count();
    if($count_shop == 0) echo 'У вас еще нет магазинов, а как следствие и товаров.';
    else
    {
        echo 'У вас '.$count_shop.' '.(($count_shop == 1)?'магазин':'магазина(ов)').'.';
    }
    ?>
</div>
