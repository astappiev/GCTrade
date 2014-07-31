<?php
use yii\helpers\Html;

$this->title = 'Калькулятор стоимости регионов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content calc">
	<h1><?= Html::encode($this->title) ?></h1>

    <div class="form-inline" role="form">
        <div class="form-group col-md-2">
            <div class="input-group">
                <span class="input-group-addon">X</span>
                <input type="text" class="form-control input-sm" id="x" placeholder="X" value="">
            </div>
        </div>
        <div class="form-group col-md-2">
            <div class="input-group">
                <span class="input-group-addon">Y</span>
                <input type="text" class="form-control input-sm" id="y" placeholder="Y" value="128">
            </div>
        </div>
        <div class="form-group col-md-2">
            <div class="input-group">
                <span class="input-group-addon">Z</span>
                <input type="text" class="form-control input-sm" id="z" placeholder="Z" value="">
            </div>
        </div>
    </div>
    <p id="error" class="text-danger"></p>
    <br /><br />
    <h4 class="text-info">Стоимость региона: <code id="result">0</code> зелени.</h4>

</div>
<?php $this->registerJS("$('input').keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            $('#error').html('Эу! Только цифры же!').show().fadeOut('slow');
            return false;
        }
    });

    $(document).on('change', 'input', function() {
        var x = parseInt($('#x').val(), 10), y = parseInt($('#y').val(), 10), z = parseInt($('#z').val(), 10);
        if(x && y && z)
        {
            var price = Math.round(x*z*10+(x*y*z*10)/256);
            if (price < 500) price = 500;
            $('#result').text(price);
        }
    });");