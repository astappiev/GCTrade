<?php

use yii\widgets\Breadcrumbs;
use app\widgets\Alert;

/**
 * Primary template
 * @var $this yii\web\View
 * @var array $content content returned from controller
 */

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

    <?= $this->render('//layouts/footer') ?>

	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
