<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\users\models\Message */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/users', 'MESSAGES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">

    <div class="btn-group pull-right" style="margin-top: 25px;">
        <?= Html::a('&larr; Назад', ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить сообщение?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-body">
            <?= $model->text ?>
        </div>
        <div class="panel-footer">
            <span class="label label-default"><?= Yii::t('app/users', '{0}', ($model->id_sender) ? $model->sender->username : 'GCTrade') ?></span>
            <span class="label label-info"><?= Yii::t('app/users', '{0, date, HH:mm dd.mm.yyyy}', $model->created_at) ?></span>
        </div>
    </div>

</div>
