<?php
use yii\helpers\Html;

$this->title = 'Управление товаром';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content item-list">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="40px"></th>
                    <th width="10%">ID</th>
                    <th class="name">Название</th>
                    <th width="10%"></th>
                </tr>
            </thead>
            <tbody>

            <?php foreach($grid as $line):
                echo '<tr>';
                echo '<td><img src="/images/items/'.$line["id"].'.png" alt="'.$line["name"].'" align="left" class="small-icon" /></td>';
                echo '<td>'.$line["id"].'</td>';
                echo '<td class="name">'.$line["name"].'</td>';
                echo '<td>'.$line["status"].'</td>';
                echo '</tr>';
            endforeach; ?>
            </tbody>
        </table>
    </div>
</div>