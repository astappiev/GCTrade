<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;

class ApiController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

	public function actionShop()
	{
        $rows = (new Query)->select('alias, name, about, description, x_cord, z_cord, logo')->from('tg_shop')->all();
        return self::renderJSON($rows);
	}

    public function actionWorld($login = null)
    {
        $world = json_decode(file_get_contents("http://srv1.greencubes.org/up/world/world"));
        $world = $world->players;

        foreach($world as $player)
        {
            unset($player->account);
            unset($player->type);
            unset($player->world);
        }

        if($login)
        {
            foreach($world as $player)
            {
                if(strtolower($player->name) == strtolower($login))
                {
                    $player = array('status' => 1, 'player' => $player);
                    return self::renderJSON($player);
                }
            }
            $status = array('status' => 0);
            return self::renderJSON($status);
        }

        return self::renderJSON($world);
    }

    public function actionItem($id)
    {
        $item = (new Query())->select('id, alias, name')->from('tg_item')->where('alias=:id', [':id' => $id])->one();
        $price = (new Query())
            ->select('count(*) as count, min(price_sell/stuck) as min, avg(price_sell/stuck) as avg, max(price_sell/stuck) as max')
            ->from('tg_price')
            ->where('id_item=:id', [':id' => $item["id"]])
            ->one();

        $cost = array('min' => $price["min"], 'avg' => $price["avg"], 'max' => $price["max"]);
        $item = array('id' => $item["alias"], 'name' => $item["name"], 'description' => null,'in_shop' => $price["count"],'cost' => $cost);
        return self::renderJSON($item);
    }

    protected function renderJSON($object)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($object, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
    }
}
