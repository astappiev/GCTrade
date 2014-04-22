<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\forms\ContactForm;

class SiteController extends Controller
{
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
				Yii::$app->session->setFlash('success', 'Благодарю за обращение. Я отвечу как можно скорее.');
			} else {
				Yii::$app->session->setFlash('error', 'Возникла ошибка при отправке Email.');
			}
			return $this->refresh();
		} else {
			return $this->render('contact', [
				'model' => $model,
			]);
		}
	}

    public function actionEconomy()
    {
        return $this->render('economy');
    }

    public function actionRail()
    {
        return $this->render('rail');
    }

    public function actionCalc()
    {
        return $this->render('calc');
    }
}