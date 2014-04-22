<?php
use yii\helpers\Html;

$this->title = 'Сравнение с дополнительным списком';
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
                    <th class="name">Название дополнительно</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = (new \yii\db\Query())
                ->select('tg_item.id as id, tg_item.alias as alias, tg_item.name as name, tg_item_usernames.name as username')
                ->from('tg_item')
                ->InnerJoin('tg_item_usernames', 'tg_item.id = tg_item_usernames.id')
                ->orderBy('tg_item_usernames.id', SORT_DESC);

            foreach($query->each() as $line):
                echo '<tr>';
                echo '<td><img src="/images/items/'.$line["alias"].'.png" alt="'.$line["name"].'" align="left" class="small-icon"></td>';
                echo '<td>'.$line["id"].'</td>';
                echo '<td class="name">'.$line["name"].'</td>';
                echo '<td>'.$line["username"].'</td>';
                echo '</tr>';
            endforeach; ?>
            </tbody>
        </table>
    </div>
</div>