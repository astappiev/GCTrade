<?php

namespace app\components;

use Yii;
use yii\web\Controller;

class ParentController extends Controller
{
    public function init()
    {
        $this->on('beforeAction', function ($event) {
            if (Yii::$app->getUser()->isGuest) {
                $request = Yii::$app->getRequest();

                if (!($request->getIsAjax() || strpos($request->getUrl(), 'login') !== false)) {
                    Yii::$app->user->setReturnUrl($request->getUrl());
                }
            }
        });
    }
}