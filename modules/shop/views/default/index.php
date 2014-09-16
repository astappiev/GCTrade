<?php

use app\helpers\Glyph;
use yii\helpers\Html;
use yii\widgets\ListView;
use app\modules\shop\Modules as Module;

/**
 * @var $this yii\web\View
 * @var $searchModel app\modules\shop\models\search\Shop
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('app/shop', 'Shops catalog');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo ListView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'nextPageLabel'     => Glyph::icon(Glyph::ICON_STEP_FORWARD),
            'prevPageLabel'     => Glyph::icon(Glyph::ICON_STEP_BACKWARD),
            'disabledPageCssClass' => 'disabled',
        ],
        'layout' => '<div class="text-right">{summary}</div>{items}<div class="text-center">{pager}</div>',
        'options' => [
            'class' => 'shop-list',
        ],
        'itemOptions' => [
            'class' => 'well',
        ],
        'itemView' => '_list_item',
    ]); ?>

</div>