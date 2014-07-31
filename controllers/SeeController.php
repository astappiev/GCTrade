<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\forms\SeeForm;
use app\models\See;
use yii\web\HeaderCollection;

class SeeController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Что бы воспользоваться данным сервисом вы должны быть авторизованы.');
            return $this->goHome();
        }

        $model = new SeeForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->AddLogin()) {
                Yii::$app->session->setFlash('success', 'Игрок '.$model->login.' добавлен, спасибо.');
            } else {
                Yii::$app->session->setFlash('error', 'Возникла ошибка при добавлении игрока.');
            }
            return $this->refresh();
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $login = See::findOne($id);
        if($login->user_id == Yii::$app->user->id)
        {
            if($login->delete()) echo 'You success delete this user.';
        }
        else echo 'You don\'t have permissions!';
    }

    public function actionLastseen($login)
    {
        $lastseen = json_decode(file_get_contents("https://greencubes.org/api.php?type=lastseen&nick=".$login));
        if($lastseen->status == 0) {
            return $lastseen->players[0]->time;
        }
        else
        {
            Yii::$app->session->setFlash('error', 'Вероятнее всего, нет связи с сервером GreenCubes.');
            return $this->redirect('see');
        }
    }
}