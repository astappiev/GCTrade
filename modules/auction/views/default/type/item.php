<?php
/**
 * Шаблон для отображаения предмета как лота аукциона.
 * @var yii\base\View $this
 * @var app\modules\auction\models\Lot $model
 */

use yii\helpers\Html;
use yii\helpers\Json;

$item = Json::decode($model->metadata, FALSE);
?>
<div class="preview item clearfix">
    <div class="grid-table">
        <div class="grid-table-border clearfix">
            <div class="grid-table-item">
                <div class="grid-table-item-border">
                    <a class="tip">
                        <img title="<?= Html::encode($item->name) ?>" src="/images/items/<?= $item->id ?>.png">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="item-desc">
        <div class="arrow"></div>
        <div class="item-desc-border">
            <div class="item-desc-block">
                <h3 class="title" style="color: <?= $item->name_color ? $item->name_color : '#fff' ?>"><?= $item->name ? $item->name : 'Не задано' ?></h3>
                <?php
                if($item->tag)
                {
                    echo '<p class="tag">';
                    foreach($item->tag as $tag) echo '<span style="color: '.$tag->color.'">'.$tag->title.'</span>';
                    echo '</p>';
                }

                if($item->desc)
                {
                    echo '<p class="desc">'.$item->desc.'</p>';
                }

                if($item->history)
                {
                    echo '<div class="history">';
                    foreach($item->history as $history) echo '<p>'.$history.'</p>';
                    echo '</div>';
                }

                if($item->strength)
                {
                    echo '<p class="strength">Прочность: <span>'.$item->strength->current.'</span> / <span>'.$item->strength->current.'</span></p>';
                }

                if($item->attr)
                {
                    echo '<div class="attr">';
                    foreach($item->attr as $attr)
                    {
                        echo '<p style="color: '.$attr->color.'">'.$attr->title;
                        if($attr->need_set) echo ' <span style="color: grey;"> (необходим весь набор)</span>';
                        echo '</p>';
                    }
                    echo '</div>';
                }

                if($item->color)
                {
                    echo '<div class="color">';
                    echo '<p style="color: #B227F7;">С эффектом декоративного свечения:</p>';
                    if($item->color->name) echo '<p style="color: '.$item->color->code.'">Цвет: '.$item->color->name.'</p>';
                    if($item->color->complexity) echo '<p>Сложность оттенка: '.$item->color->complexity.'</p>';
                    if($item->color->saturation) echo '<p>Насыщенность: '.$item->color->saturation.'</p>';
                    if($item->color->level) echo '<p>Яркость: '.$item->color->level.'</p>';
                    if($item->color->locked) echo '<p style="color: grey;">(Цвет заблокирован)</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>