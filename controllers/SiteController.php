<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;

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

    public function actionPrivacy()
	{
        return $this->render('privacy');
	}

    public function actionStatistics()
    {
        return $this->render('statistics');
    }

    public function actionDonate()
    {
        return $this->render('donate');
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