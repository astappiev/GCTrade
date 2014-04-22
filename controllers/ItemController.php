<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

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

    public function actionPage($alias)
    {
        return $this->render('page', ['url' => $alias]);
    }
}
