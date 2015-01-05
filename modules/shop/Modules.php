<?php

namespace app\modules\shop;

use Yii;

class Modules extends \yii\base\Module implements \yii\base\BootstrapInterface
{
    public $controllerNamespace = 'app\modules\shop\controllers';

    public $itemUrl = 'item';

    public $shopUrl = 'shop';

    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            /* Item rules */
            $this->itemUrl . '/<alias:[0-9.]{1,10}+>' => $this->id . '/item/view',
            $this->itemUrl => $this->id . '/item/view',
            $this->itemUrl . 's/<page:[0-9]{1,3}+>' => $this->id . '/item/index',
            $this->itemUrl . 's' => $this->id . '/item/index',
            $this->itemUrl . 's/<_action:\w+>' => $this->id . '/item/<_action>',

            /* Shop rules */
            $this->shopUrl . '/<alias:\w+>' => $this->id . '/default/view',
            $this->shopUrl => $this->id . '/default/view',
            $this->shopUrl . 's/<page:[0-9]{1,3}+>' => $this->id . '/default/index',
            $this->shopUrl . 's' => $this->id . '/default/index',
            $this->shopUrl . 's/<_action:\w+>' => $this->id . '/default/<_action>',

            'cpanel/' . $this->shopUrl . '' => $this->id . '/cpanel/index',
            'cpanel/' . $this->shopUrl . '/<_action:\w+>/<alias:\w+>' => $this->id . '/cpanel/<_action>',
            'cpanel/' . $this->shopUrl . '/<_action:\w+>' => $this->id . '/cpanel/<_action>',

            //$this->shopUrl . '/<_action:\w+>' => $this->id . '/default/<_action>',
            //$this->shopUrl . '/<_action:\w+>/<alias:\w+>' => $this->id . '/default/<_action>',

            $this->shopUrl . '/<_controller:\w+>/<_action:\w+>' => $this->id . '/<_controller>/<_action>',
        ], false);
    }

    public function init()
    {
        parent::init();
    }
}
