<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <?= $this->render('//layouts/head'); ?>
        <style>
            html > body {
                overflow: hidden;
                margin: 0;
            }

            body > .container {
                padding: 90px 15px 0;
                margin: 0 auto;
                position: absolute;
                z-index: 1;
                top: 0; left: 0; right: 0;
            }
        </style>
    </head>
    <body>
    <?= $this->render('//layouts/analytics') ?>
    <?php $this->beginBody() ?>
    <?= $this->render('//layouts/navbar') ?>
    <?= $content ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
