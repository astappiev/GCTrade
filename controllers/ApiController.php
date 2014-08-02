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

	public function actionShop($request = null)
	{
        $query = (new Query)->select('alias, name, about, description, subway, x_cord, z_cord, logo_url, updated_at')->from('tg_shop');
        if($request) {
            $query->where(['alias' => $request])->orWhere(['like', 'name', $request]);
        }
        $rows = $query->createCommand()->queryAll();

        if(!empty($rows))
        {
            for($i = count($rows); 0 < $i; --$i) {
                $rows[$i - 1]["logo_url"] = 'http://gctrade.ru/images/shop/'.$rows[$i - 1]["logo_url"];
            }
            return self::renderJSON($rows);
        }

        return self::renderJSON(['message' => 'Results not found']);
	}

    public function actionHead($login)
    {
        // TODO: Реализовать кэширование
        $src = 'http://greenusercontent.net/mc/skins/'.$login.'.png';
        $headers = @get_headers($src);

        if(strpos($headers[0], '200')) {
            $size = getimagesize($src);
            if($size[0] == "256") $src_size = 32;
            else if($size[0] == "128") $src_size = 16;
            else $src_size = 8;

            $dst_size = 32;
            $img_r = imagecreatefrompng($src);
            $dst_r = ImageCreateTrueColor($dst_size, $dst_size);

            imagecopyresampled($dst_r, $img_r, 0, 0, $src_size, $src_size, $dst_size, $dst_size, $src_size, $src_size);
            imagecopyresampled($dst_r, $img_r, 0, 0, $src_size*5, $src_size, $dst_size, $dst_size, $src_size, $src_size);
        } else {
            $dst_r = imagecreatefromjpeg(Yii::getAlias('@webroot').'/images/head_skin.jpg');
        }

        Header('Content-type: image/jpeg');
        Header('Content-Disposition: filename="'.$login.'.jpg"');
        imagejpeg($dst_r, null, 90);
    }

    public function actionBadges($login)
    {
        $json = json_decode(@file_get_contents('https://api.greencubes.org/users/'.$login))->badges;

        if(!empty($json))
        {
            $i = 0;
            foreach($json as $badge)
                $i += $badge->count;

            $width = ($i > 6)?434:64 * $i + (($i - 1) * 10);
            $height = ceil($i/6)*64 + ((ceil($i/6) - 1) * 10);
            $current_height = $current_width = 0;
            $img = imagecreatetruecolor($width, $height);
            $black = imagecolorallocate($img, 0, 0, 0);

            imagecolortransparent($img, $black); //прозрачный фон

            foreach($json as $badge)
            {
                if($badge->badgeData > 0) $id = $badge->badgeId.'.'.$badge->badgeData;
                else $id = $badge->badgeId;

                $badge_img = imagecreatefrompng(Yii::getAlias('@webroot').'/images/items/'.$id.'.png');
                imagealphablending($badge_img, false);
                imagesavealpha($badge_img, true);

                for($i = $badge->count; $i > 0; --$i)
                {
                    imagecopy($img, $badge_img, $current_width, $current_height, 0, 0, 64, 64);
                    $current_width += 64 + 10;
                    if($current_width > 434)
                    {
                        $current_width = 0;
                        $current_height += 64 + 10;
                    }
                }
            }

            Header("Content-type: image/png");
            imagepng($img);
            return imagedestroy($img);
        }

        return self::renderJSON(['message' => 'User not found or user hasn\'t badges']);
    }

    public function actionEconomy()
    {
        $query = (new Query)->select('time AS date, value')->from('tg_economy')->limit(100)->orderBy(['time' => SORT_DESC]);
        $rows = $query->createCommand(Yii::$app->db_analytics)->queryAll();

        if(!empty($rows))
            return self::renderJSON($rows);

        return self::renderJSON(['message' => 'Error response from database']);
    }

    public function actionWorld($login = null)
    {
        $world = @file_get_contents("http://srv1.greencubes.org/up/world/world");

        if(!empty($world))
        {
            $world = json_decode($world)->players;

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
                        return self::renderJSON($player);
                }
                return self::renderJSON(['message' => 'User is offline']);
            }

            return self::renderJSON($players);
        }

        return self::renderJSON(['message' => 'Impossible to get the array']);
    }

    public function actionItem($request)
    {
        $query = (new Query())->select('id, alias, name')->from('tg_item')->where('alias=:id', [':id' => $request]);
        $items = $query->createCommand()->queryAll();
        if(empty($items))
        {
            $query->orWhere(['like', 'name', $request]);
            $items = $query->createCommand()->queryAll();
        }

        if(!empty($items))
        {
            $lenght = count($items);
            for($i = 0; $i < $lenght; ++$i)
            {
                $price = (new Query())->select('count(*) as count, min(price_sell/stuck) as min, avg(price_sell/stuck) as avg, max(price_sell/stuck) as max')->from('tg_price')->where('id_item=:id', [':id' => $items[$i]["id"]])->one();

                $items[$i] = [
                    'id' => $items[$i]["alias"],
                    'name' => $items[$i]["name"],
                    'description' => null,
                    'in_shop' => $price
                ];
            }

            return self::renderJSON($items);
        }

        return self::renderJSON(['message' => 'Nothing found']);

    }

    protected function renderJSON($object)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($object, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
    }
}
