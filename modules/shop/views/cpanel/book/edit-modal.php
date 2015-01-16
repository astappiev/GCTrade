<?php
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use app\modules\shop\models\Book;
use yii\helpers\Html;
?>

<?php Modal::begin([
    'id' => 'import-modal',
    'options' => ['class' => 'import-book-modal'],
    'toggleButton' => false,
    'header' => '<h3 class="modal-title">Импорт книг из файла</h3>',
    'footer' => '<button type="button" id="import-book" class="btn btn-warning"><span class="glyphicon glyphicon-import"></span>Импорт</button>',
]); ?>
    <div class="form-group">
        <label for="file-import-book">Выбирите CSV файл прайса</label>
        <input type="file" id="file-import-book" accept=".csv, text/plain">
        <input type="hidden" id="shop-import-book" value="<?= $model->id ?>">
        <input type="hidden" id="token-import-book" value="<?= Yii::$app->request->getCsrfToken() ?>">
        <p class="help-block">Файл должен содержать в себе информацию по такому шаблону: (количество строк не ограничено)</p>
        <pre>id; название; автор; описание; стоимость продажи (или null);</pre>
    </div>
    <table class="table" id="import-book-table" style="display: none;">
        <thead>
            <tr>
                <th>ИН</th>
                <th>Название</th>
                <th>Автор</th>
                <th>Описание</th>
                <th>Стоимость</th>
                <th width="40px"></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
<?php Modal::end(); ?>

<?php Modal::begin([
        'id' => 'item-modal',
        'options' => ['class' => 'book-modal'],
        'toggleButton' => false,
        'header' => '<h3 class="modal-title">Добавить/Править книгу</h3>',
        'footer' => '<button type="button" id="submit-book-form" class="btn btn-primary"><span class="glyphicon glyphicon-cloud-upload"></span> Сохранить</button>',
    ]);

    $book_model = new Book();
    $form = ActiveForm::begin([
        'id' => 'add-book-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-8\">{input}</div>\n<div class=\"col-md-offset-4 col-md-8\">{error}</div>",
            'labelOptions' => ['class' => 'control-label col-md-4'],
        ],
    ]);
        echo Html::activeHiddenInput($book_model, 'id');
        echo Html::activeHiddenInput($book_model, 'shop_id', ['value' => $model->id]);
        echo $form->field($book_model, 'item_id')->dropDownList($book_model->getItemArray())->label('<img src="/images/items/3008.png" style="margin-top: -5px;">');
        echo $form->field($book_model, 'name')->textInput(['minlength' => 1,'maxlength' => 255]);
        echo $form->field($book_model, 'author')->textInput(['minlength' => 3,'maxlength' => 128]);
        echo $form->field($book_model, 'description')->textarea(['rows' => 3]);
        echo $form->field($book_model, 'price_sell')->textInput(['maxlength' => 11]);
    ActiveForm::end();
Modal::end(); ?>