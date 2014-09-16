<?php

/**
 * @var $this yii\web\View
 */

$this->registerCssFile('@web/css/lib/leaflet.min.css');
$this->registerJsFile('@web/js/lib/leaflet.min.js');
$this->registerJsFile('@web/js/maps.gctrade.min.js');

$this->title = \Yii::t('app/shop', 'CATALOG_SHOP');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frame-full">
    <div id="map"></div>
</div>
<?php $this->registerJS('MapsIndexShop();');