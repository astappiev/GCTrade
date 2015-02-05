<?php

namespace app\modules\shop\controllers;

use Yii;
use app\components\ParentController;
use app\modules\shop\models\Shop;
use app\modules\shop\models\search\Shop as ShopSearch;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

/**
 * DefaultController includes base Shop actions
 */
class DefaultController extends ParentController
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

    public function actionBooks()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Shop::find()->where(['type' => Shop::TYPE_BOOKS, 'status' => Shop::STATUS_PUBLISHED]),
            'sort'=> ['defaultOrder' => ['updated_at' => SORT_DESC]]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGoods()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Shop::find()->where(['type' => Shop::TYPE_GOODS, 'status' => Shop::STATUS_PUBLISHED]),
            'sort'=> ['defaultOrder' => ['updated_at' => SORT_DESC]]
        ]);

        return $this->render('index', [
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

            if(($access === true || in_array($model->status, [Shop::STATUS_DRAFT, Shop::STATUS_BLOCKED])) && $model->user_id != \Yii::$app->user->id)
                throw new ForbiddenHttpException(Yii::t('app/shop', 'SHOP_NO_PERMISSION'));

            return $model;

        } else {
            throw new NotFoundHttpException(Yii::t('app/shop', 'SHOP_NOT_FOUND'));
        }
    }
}