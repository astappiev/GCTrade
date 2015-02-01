<?php
namespace app\modules\auction\models\validators;

use linslin\yii2\curl\Curl;
use yii\validators\Validator;
use app\modules\auction\models\Lot;

class LandValidator extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = [
            'parent' => 'Регион не должен иметь родительских регионов.',
            'owner' => 'Вы должны быть владельцем региона.',
            'response' => 'Регион не существует или нет возможности соедениться с сервером.',
        ];
    }

    public function validateAttribute($model, $attribute)
    {
        if($model->type_id == Lot::TYPE_LAND) {
            $curl = new Curl();
            $model->metadata = null;
            $response = $curl->get('https://api.greencubes.org/main/regions/'.$model->$attribute);
            $data = json_decode($response);
            if($data) {
                if($data->parent) {
                    $model->addError($attribute, $this->message["parent"]);
                } elseif (!in_array(\Yii::$app->user->identity->username, $data->full_access)) {
                    $model->addError($attribute, $this->message["owner"]);
                } elseif ($data->name) {
                    $model->metadata = json_encode(["name" => $data->name,  "coordinates" => $data->coordinates]);
                }
            } else {
                $model->addError($attribute, $this->message["response"]);
            }
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $message_parent = json_encode($this->message["parent"], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $message_owner = json_encode($this->message["owner"], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $message_response = json_encode($this->message["response"], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $type_land = Lot::TYPE_LAND;
        return <<<JS
            if($('#lot-type_id').val() == $type_land) {
                $('#lot-metadata').val('');
                $.ajax({
                    url: "https://api.greencubes.org/main/regions/" + value,
                    type: "GET",
                    async: false,
                    timeout: 1000,
                    dataType: 'json',
                    success: function (data) {
                        if(data.parent) {
                            messages.push($message_parent);
                        } else if(data.full_access.indexOf(username) === -1) {
                            messages.push($message_owner);
                        } else if(data.name) {
                            $('#lot-metadata').val(JSON.stringify({ name: data.name, coordinates: data.coordinates }));
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