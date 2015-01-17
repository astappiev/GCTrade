<?php

use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model app\modules\users\models\Message
 */

$this->title = Yii::t('users', 'CREATE_MESSAGES');
$this->params['breadcrumbs'][] = ['label' => Yii::t('users', 'MESSAGES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
