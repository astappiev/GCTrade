<?php

use yii\helpers\Html;

?>
<div class="item-preview">
    <div class="grid-table">
        <div class="grid-table-border clearfix">
            <div class="grid-table-item">
                <div class="grid-table-item-border">
                    <a class="tip">
                        <img title="<?= Html::encode($metadata->name) ?>" src="/images/items/<?= $metadata->item_id ?>.png">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="item-desc">
        <div class="arrow"></div>
        <div class="item-desc-border">
            <div class="item-desc-block">
                <?php if(isset($picture_url)): ?>
                    <img src="/images/auction/<?= $picture_url ?>">
                <?php else: ?>
                    <h3 class="title" style="color: <?= $metadata->name_color ? $metadata->name_color : '#fff' ?>"><?= $metadata->name ? Html::encode($metadata->name) : 'Не задано' ?> <span class="id">(ID: <?= $metadata->item_id ?>)</span></h3>
                    <?php
                    if($metadata->tag)
                    {
                        echo '<p class="tag">';
                        foreach($metadata->tag as $tag) echo '<span style="color: '.$tag->color.'">'.$tag->title.'</span>';
                        echo '</p>';
                    }

                    if($metadata->desc)
                    {
                        echo '<p class="desc">'.$metadata->desc.'</p>';
                    }

                    echo '<br>';

                    if($metadata->history)
                    {
                        echo '<div class="history">';
                        foreach($metadata->history as $history) echo '<p>'.$history.'</p>';
                        echo '</div>';
                    }

                    if($metadata->strength)
                    {
                        echo '<p class="strength">Прочность: <span>'.$metadata->strength->current.'</span> / <span>'.$metadata->strength->current.'</span>'.($metadata->strength->mod ? (' (+'.$metadata->strength->mod.'%)') : null).'</p>';
                    }

                    if($metadata->attr)
                    {
                        echo '<div class="attr">';
                        foreach($metadata->attr as $attr)
                        {
                            echo '<p style="color: '.$attr->color.'">'.$attr->title;
                            if($attr->need_set) echo ' <span style="color: grey;"> (необходим весь набор)</span>';
                            echo '</p>';
                        }
                        echo '</div>';
                    }

                    if($metadata->color)
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