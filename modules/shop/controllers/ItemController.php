<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\web\Controller;
use app\modules\shop\models\Item;
use yii\web\NotFoundHttpException;

class ItemController extends Controller
{
	public function actionIndex()
	{
        return $this->render('index');
	}

    public function actionFull()
    {
        return $this->render('full');
    }

    public function actionView($alias = null)
    {
        if(!$alias)
            $this->redirect(['index']);

        return $this->render('view', [
            'model' => $this->findModel($alias),
        ]);
    }

    protected function findModel($alias)
    {
        $id = explode(".", $alias);
        if (($model = Item::find()->where(['id_primary' => $id[0], 'id_meta' => isset($id[1]) ? $id[1] : 0])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('shop', 'ITEM_NOT_FOUND'));
        }
    }
}
