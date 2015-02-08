<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;

class MapsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['user'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new HttpException(403, 'Вы должны быть авторизованы.');
                }
            ],
        ];
    }

	public function actionIndex()
	{
        $this->layout = 'frame';
		return $this->render('index');
	}

    public function actionUser()
    {
        return $this->render('user');
    }
}
