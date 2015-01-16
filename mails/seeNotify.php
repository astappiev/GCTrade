<?php
use yii\helpers\Html;

/**
 * @var string $username
 * @var string $see_login
 * @var string $see_description
 * @var integer $see_visited
 */
?>

<p>Привет <?= $username ?>, хотим известить о том что, один из игроков существование которых вас интерисует недавно стал неактивным.</p>
<p>Итак, мы знаем что <strong><?= Html::encode($see_login) ?></strong>, последний раз посещал игру <?= date("H:i d.m.Y", $see_visited) ?>.</p>
<br>
<p>Если вы забыли кто это, возможно вам подскажет записка которую вы оставили<?= ($see_description) ? (': <i>'.Html::encode($see_description).'</i>') : '... впрочем, вы забыли его оставить' ?>.</p>
<br>
<p>Отписаться от данной функции можно в <?= Html::a(Html::encode('личном кабинете'), 'http://gctrade.ru/user/edit') ?>.</p>