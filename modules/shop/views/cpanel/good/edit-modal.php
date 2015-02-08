<?php

use yii\bootstrap\Modal;
use app\modules\shop\models\Good;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<?php Modal::begin([
    'id' => 'import-modal',
    'options' => ['class' => 'import-good-modal'],
    'toggleButton' => false,
    'header' => '<h3 class="modal-title">Импорт товаров с файла</h3>',
    'footer' => '<button type="button" id="submit-item-form" class="btn btn-warning"><span class="glyphicon glyphicon-import"></span>Импорт</button>',
]); ?>
    <div class="form-group">
        <label for="file-import-good">Выбирите CSV файл прайса</label>
        <input type="file" id="file-import-good" accept=".csv, text/plain">
        <p class="help-block">Файл должен содержать в себе информацию по такому шаблону: (количество строк не ограничено)</p>
        <pre>id; цена продажи; цена покупки; количество за операцию;</pre>
    </div>
    <table class="table" id="add-item-table" style="display: none;">
        <thead>
        <tr>
            <th>Товар</th>
            <th>Цена продажи</th>
            <th>Цена покупки</th>
            <th>Кол-во</th>
            <th width="40px"></th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
<?php Modal::end(); ?>

<?php Modal::begin([
    'id' => 'item-modal',
    'options' => ['class' => 'item-modal'],
    'toggleButton' => false,
    'header' => '<h3 class="modal-title">Добавить товар</h3>',
    'footer' => '<button type="button" id="submit-item-form" class="btn btn-warning"><span class="glyphicon glyphicon-import"></span> Импорт</button>',
]); ?>
    <form class="form-inline" role="form" id="add-item-form">
        <input type="hidden" id="shop_id-form-good" value="<?= $model->id ?>">
        <input type="hidden" id="token-form-good" value="<?= Yii::$app->request->getCsrfToken() ?>">
        <div class="form-group">
            <label class="sr-only" for="item_id">ID товара</label>
            <input type="text" name="item_id" class="form-control" id="item_id" placeholder="ID товара">
        </div>
        <div class="form-group">
            <label class="sr-only" for="price_buy">Цена продажи</label>
            <input type="text" name="price_buy" class="form-control" id="price_buy" placeholder="Цена продажи">
        </div>
        <div class="form-group">
            <label class="sr-only" for="price_sell">Цена покупки</label>
            <input type="text" name="price_sell" class="form-control" id="price_sell" placeholder="Цена покупки">
        </div>
        <div class="form-group">
            <label class="sr-only" for="stuck">Кол-во</label>
            <input type="text" name="stuck" class="form-control" id="stuck" placeholder="Кол-во">
        </div>
        <input type="submit" class="btn btn-primary" value="Добавить">
    </form>
    <p class="text-danger" id="error-item-form"></p>
    <table class="table" id="add-item-table" style="display: none;">
        <thead>
            <tr>
                <th>Товар</th>
                <th>Цена продажи</th>
                <th>Цена покупки</th>
                <th>Кол-во</th>
                <th width="40px"></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
<?php Modal::end(); ?>

<?php Modal::begin([
    'id' => 'item-edit-modal2',
    'options' => ['class' => 'item-edit-modal2'],
    'toggleButton' => false,
    'header' => '<h3 class="modal-title">Редактировать товар</h3>',
    'footer' => '<button type="button" id="editButtonModal" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Изменить</button>',
]); ?>
    <form class="form-horizontal" role="form" id="EditItemForm">
        <input type="hidden" class="hide" id="IdHide">
        <div class="form-group">
            <label class="col-sm-4 control-label" id="name" data-id=""></label>
            <div class="col-sm-6">
                <p class="form-control-static" id="name"></p>
            </div>
        </div>
        <div class="form-group">
            <label for="Sell" class="col-sm-4 control-label">Цена продажи</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="Sell">
            </div>
        </div>
        <div class="form-group">
            <label for="Buy" class="col-sm-4 control-label">Цена покупки</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="Buy">
            </div>
        </div>
        <div class="form-group">
            <label for="Stuck" class="col-sm-4 control-label">Кол-во за сделку</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="Stuck">
            </div>
        </div>
    </form>
<?php Modal::end(); ?>

<?php Modal::begin([
    'id' => 'item-edit-modal',
    'options' => ['class' => 'item-edit-modal'],
    'toggleButton' => false,
    'header' => '<h3 class="modal-title">Править товар</h3>',
    'footer' => '<button type="button" id="submit-edit-form" class="btn btn-primary"><span class="glyphicon glyphicon-cloud-upload"></span> Сохранить</button>',
]);

    $book_model = new Good();
    $form = ActiveForm::begin([
        'id' => 'edit-item-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-8\">{input}</div>\n<div class=\"col-md-offset-4 col-md-8\">{error}</div>",
            'labelOptions' => ['class' => 'control-label col-md-4'],
        ],
    ]);
        echo Html::activeHiddenInput($book_model, 'id');
        echo Html::activeHiddenInput($book_model, 'shop_id', ['value' => $model->id]);
        echo $form->field($book_model, 'item_id')->textInput(['readonly' => true])->label('<img src="" style="margin-top: -5px;">');
        echo $form->field($book_model, 'price_sell')->textInput();
        echo $form->field($book_model, 'price_buy')->textInput();
        echo $form->field($book_model, 'stuck')->textInput();
    ActiveForm::end();
Modal::end(); ?>