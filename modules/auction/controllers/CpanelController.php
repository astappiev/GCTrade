<?php

namespace app\modules\auction\controllers;

use app\modules\auction\models\Lot;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use vova07\imperavi\actions\UploadAction as ImperaviUpload;
use yii\widgets\ActiveForm;

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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'picture-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => 'images/auction/tmp/'
            ],
            'image-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => '/images/auction/description/',
                'path' => 'images/auction/description/',
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
            'query' => Lot::find()->where(['user_id' => \Yii::$app->user->id]),
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                    'time_elapsed' => SORT_DESC
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
     * Creates a new Lot model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lot();
        $model->scenario = 'create';
        $typeArray = Lot::getTypeArray();
        $statusArray = Lot::getStatusArray();

        if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'Лот '.$model->name.', успешно создан.');
                return $this->redirect(['default/view', 'id' => $model->id]);
            } else {
                \Yii::$app->session->setFlash('error', 'Возникла ошибка при сохранении.');
                return false;
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'typeArray' => $typeArray,
                'statusArray' => $statusArray,
            ]);
        }
    }

    /**
     * Updates an existing Shop model.
     * If update is successful, the browser will be refresh this page.
     * @param string $id
     * @return mixed
     * @throws ForbiddenHttpException if you do not have permission to update this model
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, true);
        $model->scenario = 'update';
        $typeArray = Lot::getTypeArray();
        $statusArray = Lot::getStatusArray();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'Лот '.$model->name.', успешно обновлен.');
            } else {
                \Yii::$app->session->setFlash('error', 'Возникла ошибка при сохранении.');
            }
            return $this->refresh();
        } else {
            return $this->render('update', [
                'model' => $model,
                'typeArray' => $typeArray,
                'statusArray' => $statusArray,
            ]);
        }
    }

    /**
     * Deletes an existing Shop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws ForbiddenHttpException if you do not have permission to delete this model
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id, true);
        $model->delete();

        \Yii::$app->session->setFlash('success', 'Лот '.$model->name.', успешно удален.');
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
}