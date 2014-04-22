<?php
namespace app\controllers;

use Yii;
class MapsController extends CommonController
{
    public $layout = 'frame';
	public function actionIndex()
	{
		return $this->render('index');
	}
}
