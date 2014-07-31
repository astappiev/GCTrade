<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;

class MapsController extends Controller
{
	public function actionIndex()
	{
        $this->layout = 'frame';
		return $this->render('index');
	}

    public function actionUser()
    {
        if(Yii::$app->user->isGuest)
        {
            Yii::$app->session->setFlash('error', 'Вы должны быть авторизованы.');
            return $this->goHome();
        }

        $json_region = "https://api.greencubes.org/user/regions?access_token=".Yii::$app->user->identity->getAccessToken();

        $curl_handle =  curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $json_region);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'GCTrade');
        $regions = curl_exec($curl_handle);
        curl_close($curl_handle);

        return $this->render('user', ['region_list' => $regions]);
    }
}
