<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Помощь проекту';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="body-content">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Если у вас есть искренне желание поблагодарить меня за работу, вы можете отправить мне немного зелени:
        <pre class="green">/money send <span style="color: #00AAAA; font-weight: bold;">astappev</span> 500...</pre>
    </p>
    <br>
    <p>Если после этого у вас все-еще осталось желание меня поблагодарить, можете написать <?= Html::a('отзыв на форуме', 'https://forum.greencubes.org/posting.php?mode=reply&f=297&t=24524', ['target' => '_blank']) ?>.</p>
    <p>Если вы хотите помочь финансово. Благодарю, но пока я не нуждаюсь в финансах на поддержание проекта. Уверен, вы найдете куда лучший способ потратить деньги, чем отдать их неизвестному в сети. Например, купите девушке вкусное морожено ;)</p>
</div>