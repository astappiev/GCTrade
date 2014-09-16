<?php

namespace app\modules\users;

use Yii;

class Modules extends \yii\base\Module implements \yii\base\BootstrapInterface
{
    public $controllerNamespace = 'app\modules\users\controllers';

    public $usersUrl = 'user';

    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            $this->usersUrl . '/msg' => $this->id . '/message/index',
            $this->usersUrl . '/msg/<id:\d+>' => $this->id . '/message/view',
            $this->usersUrl . '/msg/<_action:\w+>' => $this->id . '/message/<_action>',
            $this->usersUrl . '/<_action:\w+>' => $this->id . '/default/<_action>',
            //$this->shopUrl . '/<_action:\w+>/<alias:\w+>' => $this->id . '/default/<_action>',

            $this->usersUrl . '/<_controller:\w+>/<_action:\w+>' => $this->id . '/<_controller>/<_action>',
        ], false);
    }

    public function init()
    {
        parent::init();

    }
}
