<?php
namespace app\modules\auction\models\validators;

use yii\validators\Validator;
use app\modules\auction\models\Lot;

class PictureValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if($model->type_id !== Lot::TYPE_LAND) {
            $data = json_decode($model->metadata);
            if(!empty($model->picture_url)) $data->picture_url = $model->picture_url;
            $model->metadata = json_encode($data);
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $type_land = Lot::TYPE_LAND;
        return <<<JS
            if($('#lot-type_id').val() !== $type_land) {
                var metadata = $('#lot-metadata');
                var picture_url = $('#lot-picture_url').val();
                var data = metadata.val() ? JSON.parse(metadata.val()) : {};
                if(picture_url) data.picture_url = picture_url;
                metadata.val(JSON.stringify(data));
            }
JS;
    }
}