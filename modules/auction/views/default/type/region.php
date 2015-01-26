<?php
/**
 * Шаблон для отображения региона как предмета аукциона.
 * @var yii\base\View $this
 * @var app\modules\auction\models\Lot $model
 */

use yii\helpers\Html;
use yii\helpers\Json;

$item = json_decode($model->metadata, false);
\app\assets\MapAsset::register($this);
?>

<div class="preview land clearfix">
    <?php if(json_last_error() === JSON_ERROR_NONE): ?>
            <div class="panel panel-default">
            <div class="panel-heading">
                <div class="map" map="<?= $item->name ?>" data-first="<?= $item->coordinates->first ?>" data-second="<?= $item->coordinates->second ?>"></div>
            </div>
            <div class="panel-body">
                <?php
                $pos1 = explode(" ", $item->coordinates->first);
                $pos2 = explode(" ", $item->coordinates->second);
                $param = [
                    'x' => abs($pos1[0]-$pos2[0]),
                    'y' => abs($pos1[1]-$pos2[1]),
                    'z' => abs($pos1[2]-$pos2[2]),
                ];
                $param['area'] = $param['x']*$param['z'];
                $param['volume'] = $param['x']*$param['y']*$param['z'];
                $param['price'] = round($param['x']*$param['z']*10+($param['x']*$param['y']*$param['z']*10)/256, 10);

                echo '<p>Площадь региона: <span class="label label-primary">'.\Yii::$app->formatter->asInteger($param['area']).'</span> блоков</p>';
                echo '<p>Объем региона: <span class="label label-primary">'.\Yii::$app->formatter->asInteger($param['volume']).'</span> блоков</p>';
                echo '<p>Базовая стоимость региона: <span class="label label-success">'.\Yii::$app->formatter->asInteger($param['price']).'</span> зелени</p>';

                echo Html::beginTag('div', ['class'=>'dropdown']);
                echo Html::button('Дополнительная информация о регионе <span class="caret"></span>',
                    ['type'=>'button', 'class'=>'btn btn-default reg-info', 'data-toggle'=>'dropdown']);
                echo \yii\bootstrap\Dropdown::widget([
                    'items' => [
                        '<li role="presentation" class="dropdown-header">Точка А: '.$item->coordinates->first.'</li>',
                        '<li role="presentation" class="dropdown-header">Точка Б: '.$item->coordinates->second.'</li>',
                        '<li class="divider"></li>',
                        '<li role="presentation" class="dropdown-header">Ширина по X: <span class="label label-info">'.$param['x'].'</span> блоков</li>',
                        '<li role="presentation" class="dropdown-header">Ширина по Z: <span class="label label-info">'.$param['z'].'</span> блоков</li>',
                        '<li role="presentation" class="dropdown-header">Высота: <span class="label label-info">'.$param['y'].'</span> блоков</li>',
                    ],
                ]);
                echo Html::endTag('div');
                ?>
            </div>
        </div>
    <?php else: ?>
        <p>Ошибка валидации Json</p>
    <?php endif; ?>
</div>