<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */
$this->registerJsFile('@web/js/jquery/jquery.countdown.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/auction.gctrade.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = 'Ваши Аукционы';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content lot-cpanel">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'class' => 'post',
        ],
        'itemView' => '_list_item',
    ]); ?>
</div>
