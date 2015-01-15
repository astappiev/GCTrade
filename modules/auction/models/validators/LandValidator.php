<?php
namespace app\modules\auction\models\validators;

use yii\validators\Validator;
use app\modules\auction\models\Lot;

class LandValidator extends Validator
{
    public $message_parent;
    public $message_owner;
    public $message_response;
    public $message_other;

    public function init()
    {
        parent::init();
        $this->message_parent = 'Регион не должен иметь родительских регионов.';
        $this->message_owner = 'Вы должны быть владельцем региона.';
        $this->message_response = 'Регион не существует или нет возможности соедениться с сервером.';
        $this->message_other = 'Непредвиденная ошибка.';
        $this->message = 'Invalid status input.';
    }

    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        /*if (!Status::find()->where(['id' => $value])->exists()) {
            $model->addError($attribute, $this->message);
        }*/
        $model->addError($attribute, $this->message_response);
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $message_parent = json_encode($this->message_parent, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $message_owner = json_encode($this->message_owner, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $message_response = json_encode($this->message_response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $message_other = json_encode($this->message_other, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $type_land = Lot::TYPE_LAND;
        $debug = $attribute->type_id;
        return <<<JS
            console.log($debug);
            if($('#lot-type_id').val() == $type_land) {
                $('#lot-metadata').val('');
                $.ajax({
                    url: "https://api.greencubes.org/main/regions/" + value,
                    type: "GET",
                    async: false,
                    timeout: 1000,
                    dataType: 'json',
                    success: function (data) {
                        var region_data = {};
                        region_data.name = data.name;
                        region_data.coordinates = data.coordinates;

                        if(data.parent) {
                            messages.push($message_parent);
                        } else if(data.full_access.indexOf(username) === -1) {
                            messages.push($message_owner);
                        } else if(data.name) {
                            $('#lot-metadata').val(JSON.stringify(region_data));
                        } else {
                            messages.push($message_other);
                        }
                    },
                    error: function() {
                        messages.push($message_response);
                    }
                });
            }
JS;
    }
}