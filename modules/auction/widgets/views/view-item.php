<?php
/**
 * Render item
 * @var yii\base\View $this
 * @var object $metadata
 */

use yii\helpers\Html;

?>
<div class="lot-preview type-item">
    <div class="grid-table">
        <div class="grid-table-border clearfix">
            <div class="grid-table-item">
                <div class="grid-table-item-border">
                    <a class="tip">
                        <img title="<?= isset($metadata->name) ? Html::encode($metadata->name) : 'Изображение лота' ?>" src="/images/items/<?= isset($metadata->item_id) ? $metadata->item_id : '86' ?>.png">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="item-desc">
        <div class="arrow"></div>
        <div class="item-desc-border">
            <div class="item-desc-block">
                <?php if(isset($metadata->picture_url)): ?>
                    <img src="/images/auction/<?= $metadata->picture_url ?>" title="<?= isset($metadata->name) ? Html::encode($metadata->name) : 'Изображение лота' ?>">
                <?php else: ?>
                    <h3 class="title" style="color: <?= isset($metadata->name_color) ? $metadata->name_color : '#fff' ?>"><?= isset($metadata->name) ? Html::encode($metadata->name) : 'Не задано' ?> <span class="id">(ID: <?= $metadata->item_id ?>)</span></h3>
                    <?php
                    if(isset($metadata->tag))
                    {
                        echo '<p class="tag">';
                        foreach($metadata->tag as $tag) echo '<span style="color: '.$tag->color.'">'.$tag->title.'</span>';
                        echo '</p>';
                    }

                    if(isset($metadata->desc))
                    {
                        echo '<p class="desc">'.$metadata->desc.'</p>';
                    }

                    echo '<br>';

                    if(isset($metadata->history))
                    {
                        echo '<div class="history">';
                        foreach($metadata->history as $history) echo '<p>'.$history.'</p>';
                        echo '</div>';
                    }

                    if(isset($metadata->strength))
                    {
                        echo '<p class="strength">Прочность: <span>'.$metadata->strength->current.'</span> / <span>'.$metadata->strength->current.'</span>'.($metadata->strength->mod ? (' (+'.$metadata->strength->mod.'%)') : null).'</p>';
                    }

                    if(isset($metadata->attr))
                    {
                        echo '<div class="attr">';
                        foreach($metadata->attr as $attr)
                        {
                            echo '<p style="color: '.$attr->color.'">'.$attr->title;
                            if(isset($attr->need_set)) echo ' <span style="color: grey;"> (необходим весь набор)</span>';
                            echo '</p>';
                        }
                        echo '</div>';
                    }

                    if(isset($metadata->color))
                    {
                        echo '<div class="color">';
                        echo '<p style="color: #B227F7;">С эффектом декоративного свечения:</p>';
                        if($metadata->color->name) echo '<p style="color: '.$metadata->color->code.'">Цвет: '.$metadata->color->name.'</p>';
                        if($metadata->color->complexity) echo '<p>Сложность оттенка: '.$metadata->color->complexity.'</p>';
                        if($metadata->color->saturation) echo '<p>Насыщенность: '.$metadata->color->saturation.'</p>';
                        if($metadata->color->level) echo '<p>Яркость: '.$metadata->color->level.'</p>';
                        if($metadata->color->locked) echo '<p style="color: grey;">(Цвет заблокирован)</p>';
                        echo '</div>';
                    }
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>