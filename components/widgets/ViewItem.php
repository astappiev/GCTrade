<?php

namespace app\components\widgets;

use yii\base\Widget;

class ViewItem extends Widget
{
    public $metadata;

    public $picture_url;

    public function init()
    {
        parent::init();
        if ($this->metadata->item_id === null) {
            $this->metadata->item_id = '86';
        }

        if($this->metadata->picture_url !== null) {
            $this->picture_url = $this->metadata->picture_url;
        }
    }

    public function run()
    {
        return $this->render('view-item', [
            'metadata' => $this->metadata,
            'picture_url' => $this->picture_url,
        ]);
    }
}