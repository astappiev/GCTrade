<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\users\models\Message */

$this->title = 'Update Message: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/users', 'MESSAGES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="message-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
