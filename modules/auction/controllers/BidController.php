<?php

namespace app\modules\auction\controllers;

use Yii;
use app\modules\auction\models\Bid;
use yii\helpers\Json;

class BidController extends \yii\web\Controller
{
    public function actionCreate()
    {
        $model = new Bid();
        $model->setAttributes(Yii::$app->request->post());
        $model->user_id = Yii::$app->user->id;
        if(!$model->lot) {
            return Json::encode(['status' => 0, 'message' => Yii::t('app/shop', 'Лот не существует'.$model->lot_id)]);
        } elseif(!is_numeric($model->cost)) {
            return Json::encode(['status' => 0, 'message' => Yii::t('app/shop', 'Сумма указана не верно')]);
        } elseif(!$model->user_id) {
            return Json::encode(['status' => 0, 'message' => Yii::t('app/shop', 'Не авторизованы или у вас нет прав')]);
        }

        if ($model->validate()) {
            if ($model->save()) {
                return Json::encode(['status' => 1, 'message' => Yii::t('app/shop', 'Ставка успешна')]);
            } else {
                return Json::encode(['status' => 0, 'message' => Yii::t('app/shop', 'Возникла ошибка при сохранении.')]);
            }
        }

        return Json::encode(['status' => 0, 'message' => Yii::t('app/shop', 'Неизвестная ошибка')]);
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
