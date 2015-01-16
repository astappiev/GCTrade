<?php

namespace app\modules\shop\controllers;

use app\modules\shop\models\Good;
use app\modules\shop\models\Item;
use Yii;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class GoodController extends \yii\web\Controller
{
    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $model = new Good();
        $status = 1;
        if(!empty($post["Good"]["id"])) {
            $model = Good::findOne($post["Good"]["id"]);
            $status = 2;
        }
        if ($model->load($post)) {
            $model->item_id = Item::findByAlias($model->item_id)->id;
            if($model->validate()) {
                if($model->save())
                    return Json::encode(['status' => $status, 'id' => $model->id]);
                else
                    return Json::encode(['status' => 0, 'message' => 'Unknown error']);
            } else {
                return Json::encode(['status' => 0, 'message' => 'Not validate']);
            }
        } else {
            return Json::encode(['status' => 0, 'message' => 'Bad Request']);
        }
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        if($model) {
            $model->item_id = $model->item->alias;
            return Json::encode(['status' => 1, 'model' => $model->attributes]);
        }
        return Json::encode(['status' => 0, 'message' => Yii::t('app/shop', 'ERROR_TRANSMITTED_DATA')]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id, true);
        if($model->delete()) {
            return Json::encode(['status' => 1, 'message' => Yii::t('app/shop', 'ITEM_REMOVED')]);
        }
        return Json::encode(['status' => 0, 'message' => Yii::t('app/shop', 'ERROR_TRANSMITTED_DATA')]);
    }

    /**
     * Finds the Good model based on its alias value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param bool $access
     * @return Good the loaded model
     * @throws ForbiddenHttpException if the model cannot be found
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $access = false)
    {
        if (($model = Good::findOne($id)) !== null) {

            if($access === true && $model->shop->user_id != \Yii::$app->user->id)
                throw new ForbiddenHttpException(Yii::t('app/shop', 'ITEM_NO_PERMISSION'));

            return $model;

        } else {
            throw new NotFoundHttpException(Yii::t('app/shop', 'ITEM_NOT_FOUND'));
        }
    }

    public function actionAvg()
    {
        $command = \Yii::$app->db->createCommand("SELECT tg_item.alias AS id, avg(price_sell/stuck) AS price_sell, avg(price_buy/stuck) as price_buy FROM tg_shop_good LEFT JOIN tg_item ON tg_shop_good.item_id = tg_item.id WHERE shop_id IN(SELECT id FROM tg_shop WHERE tg_shop.status IN(8,10)) GROUP BY item_id");
        $table = $command->queryAll();
        $row = [];
        foreach($table as $line) {
            $row[$line["id"]] = [
                "price_sell" => $line["price_sell"],
                "price_buy" => $line["price_buy"],
            ];
        }
        return Json::encode($row);
    }
}
