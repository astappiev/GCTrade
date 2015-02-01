<?php

namespace app\modules\shop\controllers;

use app\modules\shop\models\Book;
use Yii;
use app\modules\shop\models\Shop;
use app\modules\shop\models\Good;
use app\modules\shop\models\Item;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use vova07\imperavi\actions\UploadAction as ImperaviUpload;

/**
 * CpanelController includes manage your Shop actions
 */
class CpanelController extends DefaultController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'item-edit' => ['get'],
                    'item-remove' => ['get'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'logo-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => 'images/shop/tmp/'
            ],
            'image-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => 'http://gctrade.ru/images/shop/description/',
                'path' => 'images/shop/description/',
                'validatorOptions' => [
                    'maxWidth' => 1600,
                    'maxHeight' => 2000
                ]
            ]
        ];
    }

    /**
     * Lists all your Shop models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Shop::find()->where(['user_id' => \Yii::$app->user->id]),
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_ASC,
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Shop model.
     * If creation is successful, the browser will be redirected to the 'edit' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Shop();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Магазин '.$model->name.', успешно создан.');
            } else {
                Yii::$app->session->setFlash('error', 'Возникла ошибка при сохранении магазина.');
            }
            return $this->redirect(['cpanel/index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Shop model.
     * If update is successful, the browser will be refresh this page.
     * @param string $alias
     * @return mixed
     * @throws ForbiddenHttpException if you do not have permission to update this model
     */
    public function actionUpdate($alias)
    {
        $model = $this->findModel($alias, true);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Магазин '.$model->name.', успешно обновлен.');
            } else {
                Yii::$app->session->setFlash('error', 'Возникла ошибка при сохранении.');
            }
            return $this->refresh();
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Shop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $alias
     * @return mixed
     * @throws ForbiddenHttpException if you do not have permission to delete this model
     */
    public function actionDelete($alias)
    {
        $model = $this->findModel($alias, true);
        $model->delete();

        Yii::$app->session->setFlash('success', 'Магазин '.$model->name.', успешно удален.');
        return $this->redirect(['cpanel/index']);
    }

    /**
     * Edit item from Shop model.
     * @param string $alias
     * @return mixed
     */
    public function actionEdit($alias)
    {
        return $this->render('edit', [
            'model' => $this->findModel($alias, true),
        ]);
    }

    public function actionItemClear($shop_id)
    {
        if(($shop = Shop::findOne($shop_id)) === null)
            throw new NotFoundHttpException(Yii::t('app/shop', 'SHOP_NOT_FOUND'));

        if($shop->user_id !== Yii::$app->user->id)
            throw new ForbiddenHttpException(Yii::t('app/shop', 'SHOP_NO_PERMISSION'));

        if($shop->type == Shop::TYPE_GOODS)
            Good::deleteAll(['shop_id' => $shop_id]);
        else
            Book::deleteAll(['shop_id' => $shop_id]);
        return $this->redirect(['cpanel/edit', 'alias' => $shop->alias]);
    }

    public function actionExport($alias)
    {
        $model = $this->findModel($alias, true);
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$model->alias.'_'.date('Ymd').'.csv"');

        $content = '';
        foreach($model->products as $price)
        {
            if($model->type == Shop::TYPE_GOODS) {
                $price_sell = ($price->price_sell) ? $price->price_sell : 'null';
                $price_buy = ($price->price_buy) ? $price->price_buy : 'null';
                $content .= $price->item->alias . '; ' . $price_sell . '; ' . $price_buy . '; ' . $price->stuck . PHP_EOL;
            } elseif ($model->type == Shop::TYPE_BOOKS) {
                $description = ($price->description) ? $price->description : 'null';
                $price_sell = ($price->price_sell) ? $price->price_sell : 'null';
                $content .= $price->item->id_primary . '; ' . $price->name . '; ' . $price->author . '; ' . $description . '; ' . $price_sell . ';' . PHP_EOL;
            }
        }
        return $content;
    }
}