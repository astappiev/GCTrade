<?php

namespace app\modules\users;

use Yii;
use yii\base\Module;
use yii\base\BootstrapInterface;

class Modules extends Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\users\controllers';

    public $usersUrl = 'user';

    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            $this->usersUrl => $this->id . '/default/index',
            $this->usersUrl . '/msg' => $this->id . '/message/index',
            $this->usersUrl . '/msg/<id:\d+>' => $this->id . '/message/view',
            $this->usersUrl . '/msg/<_action:\w+>' => $this->id . '/message/<_action>',
            $this->usersUrl . '/<_action:\w+>' => $this->id . '/default/<_action>',
            //$this->shopUrl . '/<_action:\w+>/<alias:\w+>' => $this->id . '/default/<_action>',

            $this->usersUrl . '/<_controller:\w+>/<_action:\w+>' => $this->id . '/<_controller>/<_action>',
        ], false);

        if (!isset($app->i18n->translations['users']) && !isset($app->i18n->translations['users*'])) {
            $app->i18n->translations['users'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'system',
                'basePath' => '@app/modules/users/messages',
                'forceTranslation' => true
            ];
        }
    }
}
