<?php
use yii\helpers\Html;

$this->registerJsFile('@web/js/lib/raphael.min.js', ['yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/lib/morris.min.js', ['yii\web\JqueryAsset']);

$this->title = 'График оборота зелени, на сервере GreenCubes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>
    <div id="graph"></div>
</div>
<?php $this->registerJs("$.getJSON( '/api/economy', function(data) {
        Morris.Line({
            element: 'graph',
            data: data,
            xkey: ['date'],
            ykeys: ['value'],
            hideHover: 'auto',
            labels: ['Зелени']
        });
    }).fail(function() {
        $('#graph').append('<div class=\"alert alert-danger\" role=\"alert\">Ошибка получения данных.</div>');
    });");