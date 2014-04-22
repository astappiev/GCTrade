<?php
use yii\helpers\Html;

$this->title = 'Калькулятор стоимости регионов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <div class="form-inline" role="form">
        <div class="form-group col-md-2">
            <div class="input-group">
                <span class="input-group-addon">X</span>
                <input type="text" class="form-control input-sm" id="x" placeholder="X" value="" onchange="calc()">
            </div>
        </div>
        <div class="form-group col-md-2">
            <div class="input-group">
                <span class="input-group-addon">Y</span>
                <input type="text" class="form-control input-sm" id="y" placeholder="Y" value="128" onchange="calc()">
            </div>
        </div>
        <div class="form-group col-md-2">
            <div class="input-group">
                <span class="input-group-addon">Z</span>
                <input type="text" class="form-control input-sm" id="z" placeholder="Z" value="" onchange="calc()">
            </div>
        </div>
    </div>
    <br /><br />
    <h4 class="text-info">Стоимость региона: <code id="result">0</code> зелени.</h4>

</div>

<script type="text/javascript">
function calc()
{
    var x = parseInt($('#x').val());
    var y = parseInt($('#y').val());
    var z = parseInt($('#z').val());
    if(x && y && z)
    {
        var price = Math.round(x*z*10+(x*y*z*10)/256);
        if (price < 500) price = 500;
        $('#result').html(price);
    }
}
</script>