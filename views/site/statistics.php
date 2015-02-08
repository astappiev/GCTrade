<?php

use yii\helpers\Html;
use app\models\Economy;
use app\models\Online;

\app\assets\FlotAsset::register($this);
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
            <div id="economy" style="width:100%;height:400px;"></div>
            <div id="overview_economy" style="width:100%;height:100px;"></div>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Онлайн игроков</h3>
        </div>
        <div class="panel-body">
            <div id="online" style="width:100%;height:400px;"></div>
            <div id="overview_online" style="width:100%;height:100px;"></div>
        </div>
    </div>
</div>
<?php
    $economy = [];
    foreach(Economy::find()->orderBy('time')->all() as $line) {
        $economy[] = [$line->time * 1000/* + 3*60*60*1000*/, $line->value];
    }

    $online = [];
    foreach(Online::find()->orderBy('time')->all() as $line) {
        $online[] = [$line->time * 1000, $line->value];
    }

    $this->registerJs('
    var data_economy = '.\yii\helpers\Json::encode($economy).';
    var data_online = '.\yii\helpers\Json::encode($online).';

    var TWO_WEEK_ECONOMY_MAX = data_economy[data_economy.length - 1][0];
    var TWO_WEEK_ECONOMY_MIN = (TWO_WEEK_ECONOMY_MAX - 14*24*60*60*1000);

    var TWO_WEEK_ONLINE_MAX = data_online[data_online.length - 1][0];
    var TWO_WEEK_ONLINE_MIN = (TWO_WEEK_ONLINE_MAX - 14*24*60*60*1000);

    var MONTH_NAMES = ["Янв", "Фев", "Мрт", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Нбр", "Дек"];
    var DAY_NAMES = ["<b>Вс</b>", "Пн", "Вт", "Ср", "Чт", "Пт", "<b>Сб</b>"];

    function weekendAreas(axes) {
        var markings = [], d = new Date(axes.xaxis.min);

        d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
        d.setUTCSeconds(0);
        d.setUTCMinutes(0);
        d.setUTCHours(0);
        var i = d.getTime();

        do {
            markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
            i += 7 * 24 * 60 * 60 * 1000;
        } while (i < axes.xaxis.max);

        return markings;
    }

    var economy = $.plot($("#economy"), [{
        "label":"Зелени",
        "data": data_economy,
        "color": "green"
    }], {
        "series": {
            "lines":{
                "show":true
            }
        },
        "legend":{
            "show":false
        },
        "lines":{
            "show":true
        },
        "points":{
            "show":true,
            "radius":2
        },
        "grid":{
            "hoverable":true,
            "clickable":true,
            "autoHighlight":true,
            "color":"#90BE9D",
            "borderWidth":{
                "top":0,
                "right":0,
                "bottom":0,
                "left":0
            },
            markings: weekendAreas
        },
        xaxis:{
            mode: "time",
            monthNames: MONTH_NAMES,
            minTickSize: [1, "minute"],
            min: TWO_WEEK_ECONOMY_MIN,
            max: TWO_WEEK_ECONOMY_MAX,
        },
        "yaxis":{
            "min":0
        },
        selection: {
            mode: "x",
            color: "blue"
        },
    });

    var overview_economy = $.plot($("#overview_economy"), [{
        "data": data_economy,
        "color": "green"
    }], {
        "series":{
            "lines":{
                "show":true,
                "lineWidth":1
            },
            "shadowSize":0
        },
        "selection":{
            "mode":"x",
            color: "green"
        },
        "xaxis":{
            mode: "time",
            monthNames: MONTH_NAMES,
            minTickSize: [1, "day"],
        },
        "yaxis":{
            "min":0,
            ticks: false,
        },
        "grid":{
            "color":"#90BE9D",
            "borderWidth":{
                "top":2,
                "right":2,
                "bottom":2,
                "left":2
            },
            markings: weekendAreas
        },
    });
    overview_economy.setSelection({xaxis: {from: TWO_WEEK_ECONOMY_MIN, to: TWO_WEEK_ECONOMY_MAX}}, true);

    var online = $.plot($("#online"), [{
        "label":"Онлайн",
        "data": data_online,
        "color":"#0A67A3"
    }], {
        "series":{
            "lines":{
                "show":true
            }
        },
        "legend":{
            "show":false
        },
        "lines":{
            "show":true
        },
        "points":{
            "show":true,
            "radius":2
        },
        "grid":{
            "hoverable":true,
            "clickable":true,
            "autoHighlight":true,
            "color":"#65A6D1",
            "borderWidth":{
                "top":0,
                "right":0,
                "bottom":0,
                "left":0
            },
            markings: weekendAreas
        },
        xaxis:{
            mode: "time",
            monthNames: MONTH_NAMES,
            minTickSize: [1, "minute"],
            min: TWO_WEEK_ONLINE_MIN,
            max: TWO_WEEK_ONLINE_MAX,
        },
        "yaxis":{
            "min":0
        },
        selection: {
            mode: "x",
            color: "#0A67A3"
        },
    });
    var overview_online = $.plot($("#overview_online"), [{
        "data": data_online,
        "color": "#0A67A3"
    }], {
        "series":{
            "lines":{
                "show":true,
                "lineWidth":1
            },
            "shadowSize":0
        },
        "selection":{
            "mode":"x",
            color: "#0A67A3"
        },
        "xaxis":{
            mode: "time",
            monthNames: MONTH_NAMES,
            minTickSize: [1, "day"],
        },
        "yaxis":{
            "min":0,
            ticks: false,
        },
        "grid":{
            "color":"#0A67A3",
            "borderWidth":{
                "top":2,
                "right":2,
                "bottom":2,
                "left":2
            },
            markings: weekendAreas
        },
    });
    overview_online.setSelection({xaxis: {from: TWO_WEEK_ONLINE_MIN, to: TWO_WEEK_ONLINE_MAX}}, true);

    $("<div id=\"char-tooltip\"></div>").css({
        position: "absolute",
        display: "none",
        border: "2px solid rgba(230, 230, 230, 0.95)",
        padding: "6px",
        "background-color": "rgba(255, 255, 255, 0.9)",
        color: "#666",
        "font-size": "12px"
    }).appendTo("body");

    $("#economy").bind("plotselected", function (event, ranges) {
        $.each(economy.getXAxes(), function(_, axis) {
            var opts = axis.options;
            opts.min = ranges.xaxis.from;
            opts.max = ranges.xaxis.to;
        });
        economy.setupGrid();
        economy.draw();
        economy.clearSelection();
        overview_economy.setSelection(ranges, true);
    });

    $("#overview_economy").bind("plotselected", function (event, ranges) {
        economy.setSelection(ranges);
    });

    $("#economy").bind("dblclick", function (event, pos, item) {
        $.each(economy.getXAxes(), function(_, axis) {
            var opts = axis.options;
            opts.min = TWO_WEEK_ONLINE_MIN;
            opts.max = TWO_WEEK_ONLINE_MAX;
        });
        economy.setupGrid();
        economy.draw();
        economy.clearSelection();
        overview_economy.setSelection({xaxis: {from: TWO_WEEK_ONLINE_MIN, to: TWO_WEEK_ONLINE_MAX}}, true);
    });

    $("#overview_economy").bind("dblclick", function (event, pos, item) {
        $.each(economy.getXAxes(), function(_, axis) {
            var opts = axis.options;
            opts.min = TWO_WEEK_ONLINE_MIN;
            opts.max = TWO_WEEK_ONLINE_MAX;
        });
        economy.setupGrid();
        economy.draw();
        economy.clearSelection();
        overview_economy.setSelection({xaxis: {from: TWO_WEEK_ONLINE_MIN, to: TWO_WEEK_ONLINE_MAX}}, true);
    });

    $("#online").bind("plotselected", function (event, ranges) {
        $.each(online.getXAxes(), function(_, axis) {
            var opts = axis.options;
            opts.min = ranges.xaxis.from;
            opts.max = ranges.xaxis.to;
        });
        online.setupGrid();
        online.draw();
        online.clearSelection();
        overview_online.setSelection(ranges, true);
    });

    $("#overview_online").bind("plotselected", function (event, ranges) {
        online.setSelection(ranges);
    });

    $("#online").bind("dblclick", function (event, pos, item) {
        $.each(online.getXAxes(), function(_, axis) {
            var opts = axis.options;
            opts.min = TWO_WEEK_ONLINE_MIN;
            opts.max = TWO_WEEK_ONLINE_MAX;
        });
        online.setupGrid();
        online.draw();
        online.clearSelection();
        overview_online.setSelection({xaxis: {from: TWO_WEEK_ONLINE_MIN, to: TWO_WEEK_ONLINE_MAX}}, true);
    });

    $("#overview_online").bind("dblclick", function (event, pos, item) {
        $.each(online.getXAxes(), function(_, axis) {
            var opts = axis.options;
            opts.min = TWO_WEEK_ONLINE_MIN;
            opts.max = TWO_WEEK_ONLINE_MAX;
        });
        online.setupGrid();
        online.draw();
        online.clearSelection();
        overview_online.setSelection({xaxis: {from: TWO_WEEK_ONLINE_MIN, to: TWO_WEEK_ONLINE_MAX}}, true);
    });

    function tooltip(item) {
        if (item) {
            var x = item.datapoint[0],
                y = item.datapoint[1];

            var tooltip = "";
            tooltip += "<div class=\"char-tooltip-time\">" + (new Date(x)).toLocaleString("ru-RU", {timeZone: "Europe/Moscow", timeZoneName: "short"}) + " (по Москве)</div>";
            var timezone = new Date().getTimezoneOffset();
            if(timezone !== -240) tooltip += "<div class=\"char-tooltip-time\">" + (new Date(x)).toLocaleString("ru-RU", {timeZoneName: "short"}) + " (ваше время)</div>";
            tooltip += "<div class=\"char-tooltip-time\">" + (new Date(x)).toLocaleString("ru-RU", {timeZone: "GMT", timeZoneName: "short"}) + "</div>";
            tooltip += "<div class=\"char-tooltip-value\"><b>" + item.series.label + ": " + y + "</b></div>";

            $("#char-tooltip").html(tooltip).css({top: item.pageY+5, left: item.pageX+5}).fadeIn(200);
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