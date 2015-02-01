<?php
use yii\bootstrap\NavBar;
use app\helpers\Nav;

?>

<footer class="footer text-muted">
    <div class="container">
        <div class="pull-right">
            <?php echo Nav::widget([
                'options' => ['class' => 'nav nav-pills footer-nav'],
                'items' => [
                    ['label' => 'Политика', 'url' => ['/site/privacy']],
                    ['label' => 'API', 'url' => ['/api/index']],
                    ['label' => 'Помощь проекту', 'url' => ['/site/donate']],
                    ['label' => 'Форум поддержки', 'linkOptions' => ['target' => '_blank', 'rel' => 'external'], 'url' => 'https://forum.greencubes.org/viewtopic.php?f=267&t=24524'],
                ],
            ]); ?>
        </div>
        <div class="pull-left">
            <p>&copy; Oleg Astappev, <?= date('Y') ?></p>
        </div>
    </div>
</footer>
