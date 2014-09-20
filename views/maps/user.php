<?php
use yii\helpers\Html;

$this->registerCssFile('@web/css/lib/leaflet.min.css');
$this->registerJsFile('@web/js/lib/leaflet.min.js');
$this->registerJsFile('@web/js/jquery/jquery.autosize.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/maps.gctrade.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = 'Карта регионов пользователя';
?>

<div class="body-content usermap">
	<h1><?= Html::encode($this->title) ?></h1>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-8">
                    <dl class="dl-horizontal">
                        <dt><span id="area">0</span> блоков</dt><dd>площадь регионов</dd>
                        <dt><span id="volume">0</span> блоков</dt><dd>объем регионов</dd>
                        <dt><span id="cost">0</span> зелени</dt><dd>стоимость регионов</dd>
                        <dt><span id="percent">0</span>%</dt><dd>процент владения миром</span></dd>
                    </dl>
                    <p class="text-danger">Внимание! Функции не учитывают пересечение регионов.</p>
                </div>
                <div class="col-xs-6 col-md-4">
                    <button class="btn btn-default pull-right" data-toggle="modal" data-target="#customRegionModal">Добавить регионы</button>

                    <div class="modal fade" id="customRegionModal" tabindex="-1" role="dialog" aria-labelledby="customRegionModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                                    <h4 class="modal-title" id="customRegionModalLabel">Дополнительные регионы</h4>
                                </div>
                                <div class="modal-body">
                                    <textarea class="form-control autosize" rows="3" id="customRegionTextarea"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                    <button type="button" class="btn btn-primary" id="add-custom-regions">Сохранить</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="panel-body">
            <div id="map" style="width: 100%; height: 500px;"></div>
        </div>
    </div>
</div>
<?php $this->registerJS('MapsUserRegions();'); ?>