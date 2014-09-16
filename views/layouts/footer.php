<?php
use yii\bootstrap\NavBar;
use app\helpers\Nav;

?>

<footer class="footer text-muted">
    <div class="container">
        <p class="pull-left">&copy; Oleg Astappev, <?= date('Y') ?></p>
        <p class="pull-right">
            <?php echo Nav::widget([
                'options' => ['class' => 'footer-nav text-muted'],
                'items' => [
                    ['label' => 'Политика', 'url' => ['/site/privacy']],
                    ['label' => 'API', 'url' => ['/api/index']],
                    ['label' => 'Помощь проекту', 'url' => ['/site/donate']],
                    ['label' => 'Форум поддержки', 'linkOptions' => ['target' => '_blank', 'rel' => 'external'], 'url' => 'https://forum.greencubes.org/viewtopic.php?f=267&t=24524'],
                ],
            ]); ?>
        </p>
    </div>
</footer>
