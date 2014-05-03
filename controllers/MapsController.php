<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

class MapsController extends Controller
{
    public $layout = 'frame';
	public function actionIndex()
	{
		return $this->render('index');
	}
}
