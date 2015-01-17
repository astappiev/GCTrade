<?php

namespace app\modules\users\controllers;

use Yii;
use app\modules\users\models\Message;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Message models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Message::find()->where(['user_recipient' => Yii::$app->user->id]),
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Message model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id, true);

        if($model->status == Message::STATUS_OBTAINED || $model->status == Message::STATUS_OBTAINED_NOTIFIED)
        {
            $model->status = Message::STATUS_READS;
            $model->save();
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //var_dump($_POST);
        $model = new Message();

        if ($model->load(Yii::$app->request->get()) && $model->save()) {
            return Json::encode(['status' => 1]);
        } else if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }/* else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }*/
        return false;
    }

    /**
     * Updates an existing Message model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /*public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Deletes an existing Message model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id, true)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param bool $access
     * @return Message the loaded model
     * @throws ForbiddenHttpException if the model cannot be found
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $access = false)
    {
        if (($model = Message::findOne($id)) !== null) {

            if($access === true && $model->user_recipient != \Yii::$app->user->id)
                throw new ForbiddenHttpException(Yii::t('users', 'MESSAGE_CONTROLLER_NO_PERMISSION'));

            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('users', 'MESSAGE_CONTROLLER_NOT_FOUND'));
        }
    }
}
