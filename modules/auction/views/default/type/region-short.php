<?php
/**
 * Шаблон для отображения региона как предмета аукциона.
 * @var yii\base\View $this
 * @var app\modules\auction\models\Lot $model
 */

use yii\helpers\Html;
use yii\helpers\Json;

$item = Json::decode($model->metadata, FALSE);
\app\assets\MapAsset::register($this);
?>

<div class="preview land clearfix">
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

            echo '<p>Размер: <span class="label label-info">X '.\Yii::$app->formatter->asInteger($param['x']).'</span> <span class="label label-info">Y '.\Yii::$app->formatter->asInteger($param['y']).'</span> <span class="label label-info">Z '.\Yii::$app->formatter->asInteger($param['z']).'</span></p>';
            echo '<p>Площадь региона: <span class="label label-primary">'.\Yii::$app->formatter->asInteger($param['area']).'</span> блоков</p>';
            ?>
        </div>
    </div>
</div>