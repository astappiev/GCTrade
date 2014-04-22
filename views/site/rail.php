<?php
use yii\helpers\Html;

$this->registerJsFile('@web/js/rail.gctrade.js', ['yii\web\JqueryAsset']);

$this->title = 'Статистика железной дороги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
	<h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group pull-right" data-toggle="buttons">
        <label id="gcrc" class="btn btn-info">
            <input type="radio" name="gcrc" id="1"> GCRC
        </label>
        <label id="metro" class="btn btn-warning">
            <input type="radio" name="metro" id="0"> Метрострой
        </label>
    </div>
    <dl class="dl-horizontal">
        <dt id="length_line"></dt>
        <dd>блоков, общая протяжность магистралей</dd>

        <dt id="cb_count"></dt>
        <dd>общее количество контрольных блоков</dd>

        <dt id="station_count"></dt>
        <dd>количество станций</dd>

        <dt id="courators_count"></dt>
        <dd>количество кураторов</dd>
    </dl>

    <br/>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#courators_list" data-toggle="tab">Кураторы</a></li>
        <li><a href="#station_list" data-toggle="tab">Станции</a></li>
        <li><a href="#local_areas" data-toggle="tab">Внутрение подсети</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="courators_list"></div>
        <div class="tab-pane" id="station_list"></div>
        <div class="tab-pane" id="local_areas"></div>
    </div>

    <br/>

    <div class="panel-group" id="lists">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#lists" href="#st_bad_list">Станции без названий (<span id="st_bad_list_counter"></span>)</a></h4>
            </div>
            <div id="st_bad_list" class="panel-collapse collapse"><div class="panel-body"></div></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#lists" href="#cb_bad_list">Станции и перекрестки, с некоректным id (<span id="cb_bad_list_counter"></span>)</a></h4>
            </div>
            <div id="cb_bad_list" class="panel-collapse collapse"><div class="panel-body"></div></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#lists" href="#cb_not_owner">КБ, без владельцев (<span id="cb_not_owner_counter"></span>)</a></h4>
            </div>
            <div id="cb_not_owner" class="panel-collapse collapse"><div class="panel-body"></div></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#lists" href="#not_line">Кб, не соеденены линиями (<span id="not_line_counter"></span>)</a></h4>
            </div>
            <div id="not_line" class="panel-collapse collapse"><div class="panel-body"></div></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#lists" href="#find_cb_owner">Кб, одного владельца</a></h4>
            </div>
            <div id="find_cb_owner" class="pdouble_lineanel-collapse collapse"><div class="panel-body">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Логин владельца">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">Искать</button>
                    </span>
                        </div>
                    </div>
                    <br/><p id="find_cb_owner"></p>
                </div></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#lists" href="#full_info_cb">Вся доступная информация о КБ</a></h4>
            </div>
            <div id="full_info_cb" class="panel-collapse collapse"><div class="panel-body">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Номер cb">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">Искать</button>
                </span>
                        </div>
                    </div>
                    <br/><p id="full_info_cb"></p>
                </div></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#lists" href="#config_cb">Настройка кб</a></h4>
            </div>
            <div id="config_cb" class="panel-collapse collapse"><div class="panel-body">
                    <p>Как это работает? Создаете контрольны блоки, добвляете их на карту, связываете линиями, вбиваете номер контрольного блока, получаете список команд для его настройки. Если это станция, добвляя на карту название появляются соответствующие команды.</p>
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Номер cb">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">Сгенерировать</button>
                    </span>
                        </div>
                    </div>
                    <br/><br/><pre id="config_cb"></pre>
                </div></div>
        </div>
    </div>
</div>