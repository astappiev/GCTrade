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
        $rows = (new Query)->select('alias, name, about, description, subway, x_cord, z_cord, logo AS logo_url, updated_at')->from('tg_shop')->all();
        for($i = count($rows); 0 < $i; --$i) {
            $rows[$i - 1]["logo_url"] = 'http://gctrade.ru/images/shop/'.$rows[$i - 1]["logo_url"];
        }
        return self::renderJSON($rows);
	}

    public function actionSkins($login)
    {
        // TODO: Реализовать кэширование
        $src = 'http://greenusercontent.net/mc/skins/'.$login.'.png';
        $headers = @get_headers($src);
        $src_size = 8;
        if(!strpos($headers[0], '200')) {
            $src = 'http://gctrade.ru/images/default_char.png';
        } else {
            $size = getimagesize($src);
            if($size[0] == "128") $src_size = 16;
            if($size[0] == "256") $src_size = 32;
        }

        $dst_size = 32;
        $img_r = imagecreatefrompng($src);
        $dst_r = ImageCreateTrueColor($dst_size, $dst_size);
        imagecopyresampled($dst_r, $img_r, 0, 0, $src_size, $src_size, $dst_size, $dst_size, $src_size, $src_size);

        Header("Content-type: image/jpeg");
        imagejpeg($dst_r, null, 90);
    }

    public function actionEconomy()
    {
        $query = (new Query)->select('time AS date, value')->from('tg_economy')->limit(100)->orderBy(['time' => SORT_DESC]);
        $rows = $query->createCommand(Yii::$app->db_analytics)->queryAll();
        return self::renderJSON($rows);
    }

    public function actionWorld($login = null)
    {
        $world = json_decode(file_get_contents("http://srv1.greencubes.org/up/world/world"));
        $world = $world->players;

        $players = [];
        foreach($world as $line)
        {
            $players[] = ['name' => $line->name, 'cord' => round($line->x).' '.round($line->y).' '.round($line->z)];
        }

        if($login)
        {
            foreach($players as $player)
            {
                if(strtolower($player["name"]) == strtolower($login))
                {
                    $player = array('status' => 1, 'player' => $player);
                    return self::renderJSON($player);
                }
            }
            $status = array('status' => 0);
            return self::renderJSON($status);
        }

        return self::renderJSON($players);
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
