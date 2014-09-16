<?php

use yii\helpers\Html;
use bburim\flot\Chart as Chart;
use bburim\flot\Plugin as Plugin;
use app\models\Economy;
use app\models\Online;

$this->title = 'Статистика GreenCubes и GCTrade';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Оборот зелени</h3>
        </div>
        <div class="panel-body">
            <?php
            $data = [];
            foreach(Economy::find()->orderBy('time')->all() as $line) {
                $data[] = [$line->time * 1000, $line->value];
            }

            echo Chart::widget([
                'id' => 'economy',
                'data' => [
                    [
                        'label' => 'Зелени',
                        'data'  => $data,
                        //'lines'  => ['show' => true],
                        //'points' => ['show' => true],
                        'color' => 'green',
                    ],
                ],
                'options' => [
                    'series' => [
                        'lines' => [
                            'show' => true,
                        ],
                    ],
                    'legend' => [
                        'show' => false,
                    ],
                    'lines' => [
                        'show' => true,
                    ],
                    'points' => [
                        'show' => true,
                        'radius' => 2,
                    ],
                    'grid' => [
                        'hoverable' => true,
                        'clickable' => true,
                        'autoHighlight' => true,
                        'color' => '#90BE9D',
                        'borderWidth' => [
                            "top" => 0,
                            "right" => 0,
                            "bottom" => 0,
                            "left" => 0,
                        ]
                    ],
                    'xaxis' => [
                        'mode' => 'time',
                        'timeformat' => '%a <br> %d.%m.%Y',
                        'dayNames' => ["<b>Вс</b>", "Пн", "Вт", "Ср", "Чт", "Пт", "<b>Сб</b>"],
                        'tickLength' => 0,
                        'minTickSize' => [1, "day"],
                    ],
                    'yaxes' => [
                        'min' => 0,
                    ],
                ],
                'htmlOptions' => [
                    'style' => 'width:100%;height:400px;'
                ],
                'plugins' => [
                    Plugin::TIME,
                    Plugin::RESIZE,
                ]
            ]);
            ?>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Онлайн игроков</h3>
        </div>
        <div class="panel-body">
            <?php
            $data = [];
            foreach(Online::find()->orderBy('time')->all() as $line) {
                $data[] = [$line->time * 1000, $line->value];
            }

            echo Chart::widget([
                'id' => 'online',
                'data' => [
                    [
                        'label' => 'Онлайн',
                        'data'  => $data,
                        //'lines'  => ['show' => true],
                        //'points' => ['show' => true],
                        'color' => '#0A67A3',
                    ],
                ],
                'options' => [
                    'series' => [
                        'lines' => [
                            'show' => true,
                        ],
                    ],
                    'legend' => [
                        'show' => false,
                    ],
                    'lines' => [
                        'show' => true,
                    ],
                    'points' => [
                        'show' => true,
                        'radius' => 2,
                    ],
                    'grid' => [
                        'hoverable' => true,
                        'clickable' => true,
                        'autoHighlight' => true,
                        'color' => '#65A6D1',
                        'borderWidth' => [
                            "top" => 0,
                            "right" => 0,
                            "bottom" => 0,
                            "left" => 0,
                        ]
                    ],
                    'xaxis' => [
                        'mode' => 'time',
                        'timeformat' => '%a <br> %d.%m.%Y',
                        'dayNames' => ["<b>Вс</b>", "Пн", "Вт", "Ср", "Чт", "Пт", "<b>Сб</b>"],
                        'tickLength' => 0,
                        'minTickSize' => [1, "day"],
                    ],
                    'yaxes' => [
                        'min' => 0,
                    ],
                ],
                'htmlOptions' => [
                    'style' => 'width:100%;height:400px;'
                ],
                'plugins' => [
                    Plugin::TIME,
                    Plugin::RESIZE,
                ]
            ]);
            ?>
        </div>
    </div>
</div>

<?php $this->registerJs('
    $("<div id=\"char-tooltip\"></div>").css({
        position: "absolute",
        display: "none",
        border: "2px solid rgba(230, 230, 230, 0.8)",
        padding: "6px",
        "background-color": "rgba(255, 255, 255, 0.8)",
        color: "#666",
        "font-size": "12px"
    }).appendTo("body");

    function tooltip(item) {
        if (item) {
            var x = item.datapoint[0],
                y = item.datapoint[1];

            $("#char-tooltip").html("<div class=\"char-tooltip-time\">" + (new Date(x)).toLocaleString() + "</div><div class=\"char-tooltip-value\">" + item.series.label + ": " + y + "</div>")
                .css({top: item.pageY+5, left: item.pageX+5})
                .fadeIn(200);
        } else {
            $("#char-tooltip").hide();
        }
    }

    $("#economy").bind("plothover", function (event, pos, item) {
        tooltip(item);
    });
    $("#online").bind("plothover", function (event, pos, item) {
        tooltip(item);
    });');