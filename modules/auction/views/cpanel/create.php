<?php
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model app\modules\auction\models\Lot
 */

$this->title = 'Создание лота';
$this->params['breadcrumbs'][] = ['label' => 'Аукцион', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content lot-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'typeArray' => $typeArray,
    ]) ?>

</div>
