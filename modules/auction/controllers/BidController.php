<?php

namespace app\modules\auction\controllers;

use Yii;
use app\modules\auction\models\Bid;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;

class BidController extends \yii\web\Controller
{
    public function actionCreate()
    {
        if (!\Yii::$app->user->can('createAuction')) {
            throw new ForbiddenHttpException('Access denied');
        }


        $model = new Bid();
        $model->setAttributes(Yii::$app->request->post());

        if(!$model->lot) {
            return Json::encode(['status' => 0, 'message' => Yii::t('auction', 'BID_CONTROLLER_LOT_NOT_FOUND')]);
        } elseif(!is_numeric($model->cost)) {
            return Json::encode(['status' => 0, 'message' => Yii::t('auction', 'BID_CONTROLLER_SUM_IS_INCORRECT')]);
        } elseif($model->lot->user_id === Yii::$app->user->id) {
            return Json::encode(['status' => 0, 'message' => Yii::t('auction', 'BID_CONTROLLER_NO_PERMISSION')]);
        } elseif( $model->lot->bid->user_id == Yii::$app->user->id) {
            return Json::encode(['status' => 0, 'message' => Yii::t('auction', 'BID_CONTROLLER_IMPOSSIBLE_TWO_BITS')]);
        } elseif($model->cost < $model->lot->bid->cost) {
            return Json::encode(['status' => 0, 'message' => Yii::t('auction', 'BID_CONTROLLER_LOWER_THAN_EXISTING')]);
        } elseif($model->cost < $model->lot->price_min) {
            return Json::encode(['status' => 0, 'message' => Yii::t('auction', 'BID_CONTROLLER_LOWER_THAN_MINIMAL')]);
        } elseif($model->lot->bid && $model->cost < ($model->lot->bid->cost + $model->lot->price_step)) {
            return Json::encode(['status' => 0, 'message' => Yii::t('auction', 'BID_CONTROLLER_LOWER_THAN_STEP')]);
        }

        if ($model->validate()) {
            if ($model->save()) {
                return Json::encode(['status' => 1, 'message' => Yii::t('auction', 'BID_CONTROLLER_BID_SUCCESSFUL')]);
            } else {
                return Json::encode(['status' => 0, 'message' => Yii::t('auction', 'BID_CONTROLLER_ERROR_SAVE')]);
            }
        }

        return Json::encode(['status' => 0, 'message' => Yii::t('auction', 'BID_CONTROLLER_UNKNOWN_ERROR')]);
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
