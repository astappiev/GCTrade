<?php

/**
 * @var $this yii\web\View
 */

\app\assets\MapAsset::register($this);
$this->registerJsFile('@web/js/maps.gctrade.min.js', ['depends' => [\yii\web\JqueryAsset::className(), \app\assets\MapAsset::className()]]);

$this->title = \Yii::t('shop', 'CATALOG_SHOP');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frame-full">
    <div id="map"></div>
</div>
<?php $this->registerJS('MapsIndexShop();');