<?php
$this->registerCssFile('@web/css/lib/leaflet.min.css');
$this->registerJsFile('@web/js/lib/leaflet.min.js');
$this->registerJsFile('@web/js/maps.gctrade.min.js');

$this->title = 'Карта магазинов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frame-full">
    <div id="map"></div>
</div>
<?php $this->registerJS('your_login = "'.\Yii::$app->user->identity->username.'"; MapsIndexShop();');