<?php
use yii\helpers\Html;

$this->title = 'Калькулятор регионов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content calc">
	<h1><?= Html::encode($this->title) ?></h1>

    <form class="form-inline" role="form" autocomplete="off">
        <div class="form-group col-xs-2">
            <div class="input-group">
                <span class="input-group-addon">X</span>
                <input type="number" class="form-control input-sm number" min="1" id="x" placeholder="X" value="">
            </div>
        </div>
        <div class="form-group col-xs-2">
            <div class="input-group">
                <span class="input-group-addon">Y</span>
                <input type="number" class="form-control input-sm number" min="1" id="y" placeholder="Y" value="128">
            </div>
        </div>
        <div class="form-group col-xs-2">
            <div class="input-group">
                <span class="input-group-addon">Z</span>
                <input type="number" class="form-control input-sm number" min="1" id="z" placeholder="Z" value="">
            </div>
        </div>
        <label> или </label>
        <div class="form-group">
            <label class="sr-only" for="reg-name">Название региона</label>
            <input type="text" class="form-control input-sm" id="reg-name" placeholder="Название региона" value="">
        </div>
    </form>
    <p id="error" class="text-danger">Эу! Только цифры же!</p>
    <p id="not-found" class="text-danger">Регион не найден!</p>
    <br />
    <h5 class="text-info">Площадь региона: <code id="result-area">0</code> блоков<sup>2</sup></h5>
    <h5 class="text-info">Объем региона: <code id="result-volume">0</code> блоков<sup>3</sup></h5>
    <h4 class="text-info">Стоимость региона: <code id="result-cost">0</code> <span class="green">зелени</span></h4>

</div>
<?php $this->registerJS("var isRegion = false;
    $('input.number').keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            $('#error').css('display', 'inline').fadeOut('slow');
            return false;
        }
    });

    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $(document).on('keyup', 'input.number', calc);
    $(document).on('keyup', 'input#reg-name', function() {
        delay(calc, 750);
    });
    $(document).on('change', 'input', calc);

    function calc() {
        var text = $('#reg-name').val();
        var x, y , z;

        if(text != '')
        {
            $('#x').prop('disabled', true);
            $('#y').prop('disabled', true);
            $('#z').prop('disabled', true);
            isRegion = true;

            $.ajax({
                type: 'GET',
                url: 'https://api.greencubes.org/main/regions/' + text,
                dataType: 'json',
                async: false,
                success: function(region){
                    if(region['name']) {
                        $('#not-found').css('display', 'none');
                        $('#reg-name').parent().removeClass('has-error').addClass('has-success');
                        pos1 = region['coordinates']['first'].split(' ');
                        pos2 = region['coordinates']['second'].split(' ');

                        x = Math.abs(pos1[0]-pos2[0]),
                        y = Math.abs(pos1[1]-pos2[1]),
                        z = Math.abs(pos1[2]-pos2[2]);
                    }
                },
                error: function(){
                    $('#not-found').css('display', 'inline');
                    $('#reg-name').parent().removeClass('has-success').addClass('has-error');
                }
            });
        } else {
            if(isRegion = true)
            {
                isRegion = false;
                $('#x').prop('disabled', false);
                $('#y').prop('disabled', false);
                $('#z').prop('disabled', false);
                $('#not-found').css('display', 'none');
                $('#reg-name').parent().removeClass('has-error').removeClass('has-success');
            }
            x = parseInt($('#x').val(), 10),
            y = parseInt($('#y').val(), 10),
            z = parseInt($('#z').val(), 10);

            if(y > 128)
            {
                $('#y').val(128);
                y = 128;
            }
        }

        if(x && y && z)
        {
            var area = x * z;
            var volume = x * y * z;
            var price = Math.round(x*z*10+(x*y*z*10)/256);
            if (price < 500) price = 500;
            $('#result-area').text(price_separator(area));
            $('#result-volume').text(price_separator(volume));
            $('#result-cost').text(price_separator(price));
        } else {
            $('#result-area').text(0);
            $('#result-volume').text(0);
            $('#result-cost').text(0);
        }
    }");