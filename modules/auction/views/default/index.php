<?php

use yii\helpers\Html;
use yii\widgets\ListView;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\auction\models\search\Lot $searchModel
 */
\app\assets\SalvattoreAsset::register($this);
$this->registerJsFile('@web/js/jquery/jquery.countdown.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/auction.gctrade.js', ['depends' => [\yii\web\JqueryAsset::className(), \app\assets\MapAsset::className()]]);

$this->title = 'Текущие лоты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content auction lot-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php echo ListView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'firstPageLabel' => '<span class="glyphicon glyphicon-fast-backward"></span>',
            'lastPageLabel' => '<span class="glyphicon glyphicon-fast-forward"></span>',
            'nextPageLabel' => '<span class="glyphicon glyphicon-step-forward"></span>',
            'prevPageLabel' => '<span class="glyphicon glyphicon-step-backward"></span>',
        ],
        'layout' => "<div class=\"grid-wrapper dynamic-grid clearfix\" data-columns>{items}</div>\n{pager}\n{summary}",
        'options' => [
            'tag' => 'div',
            'class' => 'auction-grid',
        ],
        'itemOptions' => [
            'tag' => 'div',
            'class' => 'grid-lot',
        ],
        'itemView' => '_list_item',
    ]); ?>
</div>
