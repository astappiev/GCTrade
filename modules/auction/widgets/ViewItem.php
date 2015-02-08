<?php

namespace app\modules\auction\widgets;

use yii\base\Widget;

class ViewItem extends Widget
{
    public $metadata;

    public function run()
    {
        $item = json_decode($this->metadata, false);

        if(json_last_error() === JSON_ERROR_NONE) {
            return $this->render('view-item', [
                'metadata' => $item,
            ]);
        }

        return '<p>Ошибка валидации Json</p>';
    }
}