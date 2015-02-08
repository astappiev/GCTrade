<?php

namespace app\modules\users;

use Yii;
use yii\base\Module;
use yii\base\BootstrapInterface;

/**
 * Users module
 *
 * @author astappev <astappev@gmail.com>
 */
class Modules extends Module implements BootstrapInterface
{
    /**
     * @var string namespace for module
     */
    public $controllerNamespace = 'app\modules\users\controllers';

    /**
     * @var string Url prefix for module
     */
    public $usersUrl = 'user';

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            $this->usersUrl => $this->id . '/default/index',
            $this->usersUrl . '/msg' => $this->id . '/message/index',
            $this->usersUrl . '/msg/<id:\d+>' => $this->id . '/message/view',
            $this->usersUrl . '/msg/<_action:[\w\-]+>' => $this->id . '/message/<_action>',
            $this->usersUrl . '/<_action:[\w\-]+>' => $this->id . '/default/<_action>',
            $this->usersUrl . '/<username:[\w]+>' => $this->id . '/default/view',

            $this->usersUrl . '/<_controller:[\w\-]+>/<_action:[\w\-]+>' => $this->id . '/<_controller>/<_action>',
        ], false);

        if (!isset($app->i18n->translations['users'])) {
            $app->i18n->translations['users'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/modules/users/messages',
                'forceTranslation' => true
            ];
        }
    }
}
