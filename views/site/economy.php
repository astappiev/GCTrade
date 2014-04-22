<?php
use yii\helpers\Html;

$this->registerJsFile('@web/js/raphael-min.js', ['yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/morris-0.4.3.min.js', ['yii\web\JqueryAsset']);
$this->registerCssFile('@web/css/morris.css', ['yii\web\JqueryAsset']);

$this->title = 'Экономика GreenCubes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

	<p>График оборота зелени, на сервере GreenCubes:</p>
    <div id="graph"></div>

    <?php
    $this->registerJs("Morris.Line({
        element: 'graph',
        data: graph_data,
        xkey: 'date',
        ykeys: 'a',
        hideHover: 'auto',
        labels: ['Зелени']
        });"); ?>

</div>

<script type="text/javascript">
var graph_data = [
<?php
    $query = (new \yii\db\Query)->select('time, value')->from('tg_other_economy')->limit(100)->orderBy(['time' => SORT_DESC]);
    $rows = $query->createCommand()->queryAll();
    foreach($rows as $line)
    {
        echo "{date: '".$line["time"]."', a: ".$line["value"]."},";
    }
?>
];
</script>