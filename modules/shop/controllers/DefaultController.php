<?php

namespace app\modules\shop\controllers;

use Yii;
use app\modules\shop\models\Shop;
use app\modules\shop\models\search\Shop as ShopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

/**
 * DefaultController includes base Shop actions
 */
class DefaultController extends Controller
{
    /**
     * Lists all Shop models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Map with all Shop models.
     * @return mixed
     */
    public function actionMap()
    {
        $this->layout = 'frame';
        return $this->render('map');
    }

    /**
     * Displays a single Shop model.
     * @param string $alias
     * @return mixed
     */
    public function actionView($alias = null)
    {
        if(!$alias)
            $this->redirect(['index']);

        return $this->render('view', [
            'model' => $this->findModel($alias),
        ]);
    }

    /**
     * Finds the Shop model based on its alias value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $alias
     * @param bool $access
     * @return Shop the loaded model
     * @throws ForbiddenHttpException if the model cannot be found
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($alias, $access = false)
    {
        if (($model = Shop::find()->where(['alias' => $alias])->one()) !== null) {

            if($access === true && $model->owner != \Yii::$app->user->id)
                throw new ForbiddenHttpException(Yii::t('app/shop', 'SHOP_NO_PERMISSION'));

            return $model;

        } else {
            throw new NotFoundHttpException(Yii::t('app/shop', 'SHOP_NOT_FOUND'));
        }
    }
}