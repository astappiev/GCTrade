<?php

namespace app\modules\shop;

use Yii;

/**
 * Shop module
 *
 * @author astappev <astappev@gmail.com>
 */
class Modules extends \yii\base\Module implements \yii\base\BootstrapInterface
{
    /**
     * @var string namespace for module
     */
    public $controllerNamespace = 'app\modules\shop\controllers';

    /**
     * @var string Url prefix for item
     */
    public $itemUrl = 'item';

    /**
     * @var string Url prefix for module
     */
    public $shopUrl = 'shop';

    /**
     * @inheritdoc
     */
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
            'cpanel/' . $this->shopUrl . '/<_controller:(book|good)>/<_action:\w+>' => $this->id . '/<_controller>/<_action>',
            'cpanel/' . $this->shopUrl . '/<_action:\w+>/<alias:\w+>' => $this->id . '/cpanel/<_action>',
            'cpanel/' . $this->shopUrl . '/<_action:\w+>' => $this->id . '/cpanel/<_action>',

            //$this->shopUrl . '/<_action:\w+>' => $this->id . '/default/<_action>',
            //$this->shopUrl . '/<_action:\w+>/<alias:\w+>' => $this->id . '/default/<_action>',

            $this->shopUrl . '/<_controller:\w+>/<_action:\w+>' => $this->id . '/<_controller>/<_action>',
        ], false);

        if (!isset($app->i18n->translations['shop'])) {
            $app->i18n->translations['shop'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/modules/shop/messages',
                'forceTranslation' => true
            ];
        }
    }
}
