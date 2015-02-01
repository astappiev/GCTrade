<?php

namespace app\modules\auction\widgets;

use yii\base\Widget;

class ViewRegion extends Widget
{
    public $metadata;

    public $short = false;

    public function run()
    {
        $item = json_decode($this->metadata, false);

        if(json_last_error() === JSON_ERROR_NONE) {
            return $this->render('view-region', [
                'metadata' => $item,
                'short' => $this->short
            ]);
        }

        return '<p>Ошибка валидации Json</p>';
    }
}