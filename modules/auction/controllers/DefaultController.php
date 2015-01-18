<?php

namespace app\modules\auction\controllers;

use Yii;
use app\modules\auction\models\Lot;
use app\modules\auction\models\search\Lot as LotSearch;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * AuctionController implements the CRUD actions for Lot model.
 */
class DefaultController extends Controller
{
    /**
     * Lists all Lot models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LotSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Lot model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Shop model based on its alias value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $alias
     * @param bool $access
     * @return Lot the loaded model
     * @throws ForbiddenHttpException if the model cannot be found
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $access = false)
    {
        if (($model = Lot::findOne($id)) !== null) {

            if(($access === true || in_array($model->status, [Lot::STATUS_DRAFT, Lot::STATUS_BLOCKED])) && $model->user_id != \Yii::$app->user->id)
                throw new ForbiddenHttpException(Yii::t('app/shop', 'SHOP_NO_PERMISSION'));

            return $model;

        } else {
            throw new NotFoundHttpException(Yii::t('app/shop', 'SHOP_NOT_FOUND'));
        }
    }
}
