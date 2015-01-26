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
$this->registerJsFile('@web/js/auction.gctrade.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = 'Текущие лоты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content lot-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="clearfix">
        <div id="columns" data-columns>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <?php /*$this->registerJs(<<<JS
function append(title, content){
  var grid = document.querySelector('#columns');
  var item = document.createElement('div');
  var h = '<div class="panel panel-primary">';
      h += '<div class="panel-heading">';
      h += title;
      h += '</div>';
      h += '<div class="panel-body">';
      h += content;
      h += '</div>';
      h += '</div>';
  salvattore['append_elements'](grid, [item])
  item.outerHTML = h;
}

$.getJSON("https://www.googleapis.com/books/v1/volumes?q=inauthor:Ernest+Hemingway&callback=?", function(data){
  $(data.items).each(function(i,book){
      append(book.volumeInfo.title, book.volumeInfo.description);
  });
});
JS
)*/
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'firstPageLabel' => '<span class="glyphicon glyphicon-fast-backward"></span>',
            'lastPageLabel' => '<span class="glyphicon glyphicon-fast-forward"></span>',
            'nextPageLabel' => '<span class="glyphicon glyphicon-step-forward"></span>',
            'prevPageLabel' => '<span class="glyphicon glyphicon-step-backward"></span>',
        ],
        'layout' => "<div class=\"posts dynamic-grid clearfix\" data-columns>{items}</div>\n{pager}\n{summary}",
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
