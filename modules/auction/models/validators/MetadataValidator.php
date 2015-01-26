<?php
namespace app\modules\auction\models\validators;

use yii\validators\Validator;
use app\modules\auction\models\Lot;

class MetadataValidator extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = [
            'not_valid' => 'Данные поля, не соответствуют валидации json.',
            'not_have_item_id' => 'Массив должен содержать, обязательное поле "item_id".',
            'not_have_picture_url' => 'Массив должен содержать, обязательное поле "picture_url".',
            'not_have_name' => 'Массив должен содержать, обязательное поле "name".',
            'not_have_coordinates' => 'Массив должен содержать, обязательное поле "coordinates".',
            'not_have_id' => 'Массив должен содержать, обязательное поле "id".',
        ];
    }

    public function validateAttribute($model, $attribute)
    {
        if($model->type_id == Lot::TYPE_ITEM_IMAGE) {
            $data = json_decode($model->$attribute);
            if($data === null) {
                $model->addError($attribute, $this->message["not_valid"]);
            } else if (!$data->item_id) {
                $model->addError($attribute, $this->message["not_have_item_id"]);
            } else if (!$data->picture_url) {
                $model->addError($attribute, $this->message["not_have_picture_url"]);
            }
        } else if($model->type_id == Lot::TYPE_PROJECT || $model->type_id == Lot::TYPE_OTHER) {
            $data = json_decode($model->$attribute);
            if($data === null) {
                $model->addError($attribute, $this->message["not_valid"]);
            } else if (!$data->picture_url) {
                $model->addError($attribute, $this->message["not_have_picture_url"]);
            }
        } else if($model->type_id == Lot::TYPE_LAND) {
            $data = json_decode($model->$attribute);
            if($data === null) {
                $model->addError($attribute, $this->message["not_valid"]);
            } else if (!$data->name) {
                $model->addError($attribute, $this->message["not_have_name"]);
            } else if (!$data->coordinates) {
                $model->addError($attribute, $this->message["not_have_coordinates"]);
            }
        } else if($model->type_id == Lot::TYPE_ITEM) {
            $data = json_decode($model->$attribute);
            if($data === null) {
                $model->addError($attribute, $this->message["not_valid"]);
            } else if (!$data->name) {
                $model->addError($attribute, $this->message["not_have_name"]);
            } else if (!$data->item_id) {
                $model->addError($attribute, $this->message["not_have_id"]);
            }
        }
    }
}