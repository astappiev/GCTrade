<?php
use yii\widgets\Breadcrumbs;
use app\widgets\Alert;

$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render('//layouts/head'); ?>
</head>
<body>
    <?= $this->render('//layouts/analytics') ?>
    <?php $this->beginBody() ?>
    <?= $this->render('//layouts/navbar') ?>

    <div class="container">
        <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>

	<footer class="footer text-muted">
		<div class="container">
		<p class="pull-left">&copy; Oleg Astappev <?= date('Y') ?></p>
		<p class="pull-right"><?= Yii::powered() ?></p>
		</div>
	</footer>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
