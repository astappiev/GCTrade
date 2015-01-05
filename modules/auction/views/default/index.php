<?php

use yii\helpers\Html;
use yii\widgets\ListView;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\auction\models\search\Lot $searchModel
 */
$this->registerJsFile('@web/js/jquery/jquery.countdown.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/auction.gctrade.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = 'Текущие лоты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content lot-index">

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
        'layout' => "<div class=\"posts clearfix\">{items}</div>\n{pager}\n{summary}",
        'options' => [
            'tag' => 'div',
            'class' => 'auction',

        ],
        'itemOptions' => [
            'tag' => 'div',
            'class' => 'post col-xs-6',
        ],
        'itemView' => '_list_item',
    ]); ?>
</div>
