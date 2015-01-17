<?php
namespace app\modules\auction\models\validators;

use yii\validators\Validator;
use app\modules\auction\models\Lot;

class ItemValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if($model->type_id == Lot::TYPE_ITEM_IMAGE) {
            $model->metadata = json_encode(["item_id" => str_replace(",", ".", $model->$attribute), "picture_url" => $model->picture_url]);
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $type_item_image = Lot::TYPE_ITEM_IMAGE;
        return <<<JS
            if($('#lot-type_id').val() == $type_item_image) {
                $('#lot-metadata').val(JSON.stringify({ item_id: $('#lot-item_id').val().replace(/,/g,"."), picture_url: $('#lot-picture_url').val() }));
            }
JS;
    }
}