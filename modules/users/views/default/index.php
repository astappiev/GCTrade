<?php
use yii\helpers\Html;
use app\modules\shop\models\Shop;

/**
 * @var yii\web\View $this
 * @var app\modules\users\models\User $model
 */

$this->title = Yii::t('users', 'MESSAGE_VIEWS_INDEX_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= $this->title ?></h1>
    <dl class="dl-horizontal">
        <dt><?= Yii::t('users', 'MESSAGE_VIEWS_INDEX_USERNAME') ?></dt><dd><?= Html::encode($model->username) ?></dd>
        <dt><?= Yii::t('users', 'MESSAGE_VIEWS_INDEX_EMAIL') ?></dt><dd><?= Html::encode($model->email) ?></dd>
        <dt><?= Yii::t('users', 'MESSAGE_VIEWS_INDEX_CREATED') ?></dt><dd><?= Yii::$app->formatter->asDatetime($model->created_at) ?></dd>
        <dt><?= Yii::t('users', 'MESSAGE_VIEWS_INDEX_SHOP_COUNT') ?></dt><dd><?= Shop::find()->where('user_id=:id', [':id' => Yii::$app->user->id])->count() ?></dd>
    </dl>
</div>
