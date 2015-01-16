<?php

namespace app\modules\shop\controllers;

use app\modules\shop\models\Book;
use app\modules\shop\models\Item;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class BookController extends \yii\web\Controller
{
    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $model = new Book();
        $status = 1;
        if(!empty($post["Book"]["id"])) {
            $model = Book::findOne($post["Book"]["id"]);
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
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        } else {
            return Json::encode(['status' => 0, 'message' => 'Bad Request']);
        }
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        if($model) {
            $model->item_id = $model->item->id_primary;
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
     * Finds the Book model based on its alias value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param bool $access
     * @return Book the loaded model
     * @throws ForbiddenHttpException if the model cannot be found
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $access = false)
    {
        if (($model = Book::findOne($id)) !== null) {

            if($access === true && $model->shop->user_id != \Yii::$app->user->id)
                throw new ForbiddenHttpException(Yii::t('app/shop', 'ITEM_NO_PERMISSION'));

            return $model;

        } else {
            throw new NotFoundHttpException(Yii::t('app/shop', 'ITEM_NOT_FOUND'));
        }
    }
}
