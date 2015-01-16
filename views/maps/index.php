<?php
\app\assets\MapAsset::register($this);
$this->registerJsFile('@web/js/maps.gctrade.js', ['depends' => [\yii\web\JqueryAsset::className(), \app\assets\MapAsset::className()]]);

$this->title = 'Карта магазинов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frame-full">
    <div id="map"></div>
</div>
<?php $this->registerJS('your_login = "'.\Yii::$app->user->identity->username.'"; MapsIndexShop();');