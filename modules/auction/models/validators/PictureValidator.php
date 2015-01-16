<?php
namespace app\modules\auction\models\validators;

use yii\validators\Validator;
use app\modules\auction\models\Lot;

class PictureValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if($model->type_id == Lot::TYPE_ITEM_IMAGE || $model->type_id == Lot::TYPE_PROJECT || $model->type_id == Lot::TYPE_OTHER) {
            $model->metadata = json_encode(["item_id" => $model->item_id, "picture_url" => $model->$attribute]);
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $type_prject = Lot::TYPE_PROJECT;
        $type_item_image = Lot::TYPE_ITEM_IMAGE;
        $type_other = Lot::TYPE_OTHER;
        return <<<JS
            var type = $('#lot-type_id').val();
            if(type == $type_item_image || type === $type_prject || type === $type_other) {
                $('#lot-metadata').val(JSON.stringify({ item_id: $('#lot-item_id').val(), picture_url: $('#lot-picture_url').val() }));
            }
JS;
    }
}