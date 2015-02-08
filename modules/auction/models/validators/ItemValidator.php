<?php
namespace app\modules\auction\models\validators;

use yii\validators\Validator;
use app\modules\auction\models\Lot;

class ItemValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if($model->type_id == Lot::TYPE_ITEM) {
            $data = json_decode($model->metadata);
            $data->item_id = str_replace(",", ".", $model->item_id);
            $model->metadata = json_encode($data);
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $type_item = Lot::TYPE_ITEM;
        return <<<JS
            if($('#lot-type_id').val() == $type_item) {
                var metadata = $('#lot-metadata');
                var item_id = $('#lot-item_id');
                var data = metadata.val() ? JSON.parse(metadata.val()) : {};
                var value = item_id.val().replace(/,/g,".");
                item_id.val(value);
                data.item_id = value;
                metadata.val(JSON.stringify(data));
            }
JS;
    }
}