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

        header("Status: 404 Not Found");
        header('HTTP/1.0 404 Not Found');
        return self::renderJSON(['message' => 'Results not found']);
	}

    public function actionSkin($login)
    {
        // TODO: Реализовать кэширование
        $src = 'http://greenusercontent.net/mc/skins/'.$login.'.png';
        Header('Access-Control-Allow-Origin: *');
        Header("Content-type: image/png");
        echo file_get_contents($src);
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

        $width_badges = 32;
        $in_line = 14;
        $margin = 8;
        $line_width = ($width_badges * $in_line) + ($margin * ($in_line - 1));
        if(!empty($json))
        {
            $i = 0;
            foreach($json as $badge)
                $i += $badge->count;

            $width = ($i > $in_line) ? $line_width : ($width_badges * $i + (($i - 1) * $margin));
            $height = ceil($i/$in_line) * $width_badges + ((ceil($i/$in_line) - 1) * $margin);
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
                    imagecopy($img, $badge_img, $current_width, $current_height, 0, 0, $width_badges, $width_badges);
                    $current_width += $width_badges + $margin;
                    if($current_width > $line_width)
                    {
                        $current_width = 0;
                        $current_height += $width_badges + $margin;
                    }
                }
            }

            Header("Content-type: image/png");
            imagepng($img);
            return imagedestroy($img);
        }

        header("Status: 404 Not Found");
        header('HTTP/1.0 404 Not Found');
        return self::renderJSON(['message' => 'User not found or user hasn\'t badges']);
    }

    public function actionRegions()
    {
        if(Yii::$app->user->isGuest)
            return self::renderJSON(['message' => 'You must be logged in']);

        if(Yii::$app->user->identity->getAccessToken() == null)
            return self::renderJSON(['message' => 'It is a trouble with accessToken']);

        $request = "https://api.greencubes.org/user/regions?access_token=".Yii::$app->user->identity->getAccessToken();

        $curl_handle =  curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $request);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'GCTrade');
        $regions = curl_exec($curl_handle);
        curl_close($curl_handle);

        return self::renderJSON(json_decode($regions));
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

        header("Status: 404 Not Found");
        header('HTTP/1.0 404 Not Found');
        return self::renderJSON(['message' => 'Impossible to get the array']);
    }

    public function actionItem($request)
    {
        $id = explode(".", $request);
        $query = (new Query())->select('id, id_primary, id_meta, name')->from('tg_item')->where('tg_item.id_primary=:id_primary AND tg_item.id_meta=:id_meta', [':id_primary' => $id[0], ':id_meta' => isset($id[1]) ? $id[1] : 0]);
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
                $price = (new Query())->select('count(*) as count, min(price_sell/stuck) as min, avg(price_sell/stuck) as avg, max(price_sell/stuck) as max')->from('tg_shop_good')->where('item_id=:id', [':id' => $items[$i]["id"]])->one();

                $items[$i] = [
                    'id' => ($items[$i]["id_meta"]) ? $items[$i]["id_primary"].'.'.$items[$i]["id_meta"] : $items[$i]["id_primary"],
                    'name' => $items[$i]["name"],
                    'description' => null,
                    'in_shop' => $price
                ];
            }

            return self::renderJSON($items);
        }

        header("Status: 404 Not Found");
        header('HTTP/1.0 404 Not Found');
        return self::renderJSON(['message' => 'Nothing found']);
    }

    public function actionPrice($request)
    {
        $id = explode(".", $request);
        $query = (new Query())->select('id, id_primary, id_meta, name')->from('tg_item')->where('tg_item.id_primary=:id_primary AND tg_item.id_meta=:id_meta', [':id_primary' => $id[0], ':id_meta' => isset($id[1]) ? $id[1] : 0]);
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
                $prices = (new Query())->select('shop_id, price_sell, price_buy, stuck')->from('tg_shop_good')->where('item_id=:id', [':id' => $items[$i]["id"]])->orderBy('IFNULL(`price_sell`, \'1\') / `stuck` ASC')->createCommand()->queryAll();

                if(!empty($prices)) {
                    $lenght_p = count($prices);
                    for ($i_p = 0; $i_p < $lenght_p; ++$i_p) {

                        $shop = (new Query())->select('alias, name, logo_url')->from('tg_shop')->where('id=:id', [':id' => $prices[$i_p]["shop_id"]])->one();

                        $shop["logo_url"] = ($shop["logo_url"] == null) ? null : 'http://gctrade.ru/images/shop/'.$shop["logo_url"];
                        $shop["shop_url"] = 'http://gctrade.ru/shop/'.$shop["alias"];
                        unset($shop["alias"]);

                        $prices[$i_p] = [
                            'price_sell' => $prices[$i_p]["price_sell"],
                            'price_buy' => $prices[$i_p]["price_buy"],
                            'stuck' => $prices[$i_p]["stuck"],
                            'shop' => $shop
                        ];
                    }
                }

                $id = ($items[$i]["id_meta"]) ? $items[$i]["id_primary"].'.'.$items[$i]["id_meta"] : $items[$i]["id_primary"];
                $items[$i] = [
                    'id' => $id,
                    'name' => $items[$i]["name"],
                    'description' => null,
                    'image_url' => 'http://gctrade.ru/images/items/'.$id.'.png',
                    'in_shop' => $prices
                ];
            }

            return self::renderJSON(($lenght == 1) ? $items[0] : $items);
        }

        header("Status: 404 Not Found");
        header('HTTP/1.0 404 Not Found');
        return self::renderJSON(['message' => 'Nothing found']);
    }

    protected function renderJSON($object)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($object, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
    }
}
