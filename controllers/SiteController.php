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

    public function actionRaschet()
	{
        $this->redirect(Url::to('http://raschet.gctrade.ru'), NULL);
	}

	public function actionForum()
	{
        $this->redirect(Url::to('https://forum.greencubes.org/viewtopic.php?f=267&t=24524'), NULL);
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