<?php
namespace app\controllers;

use linslin\yii2\curl\Curl;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\db\Query;
use yii\web\Response;

class ApiController extends Controller
{

    public function behaviors()
    {
        return [
            'json' => [
                'class' => 'yii\filters\ContentNegotiator',
                'except' => ['index'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'cors' => [
                'class' => 'yii\filters\Cors',
                'except' => ['index'],
                'cors' => [
                    'Access-Control-Request-Method' => ['POST', 'GET'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600,
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

	public function actionShop($request = null)
	{
        $query = (new Query)->select('alias, name, about, description, subway, x_cord, z_cord, logo_url, updated_at')->from('tg_shop');
        if ($request) $query->where(['alias' => $request])->orWhere(['like', 'name', $request]);
        $rows = $query->createCommand()->queryAll();

        for ($i = count($rows); 0 < $i; --$i) {
            $rows[$i - 1]["image_url"] = Url::base(true) . '/images/shop/' . $rows[$i - 1]["logo_url"];
        }

        if (!empty($rows)) return count($rows) === 1 ?$rows[0] : $rows;

        Yii::$app->response->statusCode = 404;
        return ['message' => 'Results not found'];
	}

    public function actionNomenclature($id = null)
    {
        $query = (new Query)->select('alias as id, name')->from('tg_item');
        if ($id) $query->where(['alias' => $id]);
        $rows = $query->createCommand()->queryAll();

        for ($i = count($rows); 0 < $i; --$i) {
            $rows[$i - 1]["image_url"] = Url::base(true) . '/images/items/' . $rows[$i - 1]["id"] . '.png';
        }

        if (!empty($rows)) return count($rows) === 1 ? $rows[0] : $rows;

        Yii::$app->response->statusCode = 404;
        return ['message' => 'Results not found'];
    }

    public function actionHead($login)
    {
        $src = 'http://greenusercontent.net/mc/skins/'.$login.'.png';
        $headers = @get_headers($src);

        if (strpos($headers[0], '200')) {
            $size = getimagesize($src);
            if ($size[0] == "256") $src_size = 32;
            elseif ($size[0] == "128") $src_size = 16;
            else $src_size = 8;

            $dst_size = 32;
            $img_r = imagecreatefrompng($src);
            $image = ImageCreateTrueColor($dst_size, $dst_size);

            imagecopyresampled($image, $img_r, 0, 0, $src_size, $src_size, $dst_size, $dst_size, $src_size, $src_size);
            imagecopyresampled($image, $img_r, 0, 0, $src_size*5, $src_size, $dst_size, $dst_size, $src_size, $src_size);
        } else {
            $image = imagecreatefromjpeg(Yii::getAlias('@webroot').'/images/head_skin.jpg');
        }

        Header('Pragma: public');
        Header('Cache-Control: max-age=86400');
        Header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
        Header('Content-type: image/jpeg');
        Header('Content-Disposition: filename="' . $login . '.jpg"');
        imagejpeg($image, null, 90);
        return;
    }

    public function actionBadges($login)
    {
        $response = (new Curl())->get('https://api.greencubes.org/users/' . $login);
        $json = json_decode($response)->badges;

        if (empty($json)) {
            Yii::$app->response->statusCode = 404;
            return ['message' => 'User not found or user hasn\'t badges'];
        } else {
            $width_badges = 32;
            $in_line = 14;
            $margin = 8;
            $line_width = ($width_badges * $in_line) + ($margin * ($in_line - 1));

            $i = 0;
            foreach($json as $badge)
                $i += $badge->count;

            $width = ($i > $in_line) ? $line_width : ($width_badges * $i + (($i - 1) * $margin));
            $height = ceil($i/$in_line) * $width_badges + ((ceil($i/$in_line) - 1) * $margin);
            $current_height = $current_width = 0;
            $img = imagecreatetruecolor($width, $height);
            $black = imagecolorallocate($img, 0, 0, 0);

            imagecolortransparent($img, $black); //прозрачный фон

            foreach ($json as $badge) {
                $id = $badge->badgeData > 0 ? $badge->badgeId.'.'.$badge->badgeData : $badge->badgeId;
                $badge_img = imagecreatefrompng(Yii::getAlias('@webroot').'/images/items/'.$id.'.png');
                imagealphablending($badge_img, false);
                imagesavealpha($badge_img, true);

                for ($i = $badge->count; $i > 0; --$i) {
                    imagecopy($img, $badge_img, $current_width, $current_height, 0, 0, $width_badges, $width_badges);
                    $current_width += $width_badges + $margin;
                    if ($current_width > $line_width) {
                        $current_width = 0;
                        $current_height += $width_badges + $margin;
                    }
                }
            }

            Header('Pragma: public');
            Header('Cache-Control: max-age=86400');
            Header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
            Header('Content-Type: image/png');
            Header('Content-Disposition: filename="badge_' . $login . '.png"');
            imagepng($img);
            imagedestroy($img);
        }

        return null;
    }

    public function actionRegions()
    {
        if (Yii::$app->user->isGuest) {
            return ['message' => 'You must be logged in'];
        }

        if (Yii::$app->user->identity->getAccessToken() == null) {
            return ['message' => 'It is a trouble with accessToken'];
        }

        $response = (new Curl())->get('https://api.greencubes.org/user/regions?access_token=' . Yii::$app->user->identity->getAccessToken());
        return json_decode($response);
    }

    public function actionWorld($login = null)
    {
        $world = (new Curl())->get('http://srv1.greencubes.org/up/world/world');
        if (!empty($world)) {
            $world = json_decode($world)->players;
            $players = [];
            foreach ($world as $line) {
                $players[] = ['name' => $line->name, 'cord' => round($line->x).' '.round($line->y).' '.round($line->z)];
            }

            if ($login) {
                foreach ($players as $player) {
                    if(strtolower($player["name"]) === strtolower($login)) {
                        return $player;
                    }
                }
                return ['message' => 'User is offline'];
            }
            return $players;
        }

        Yii::$app->response->statusCode = 404;
        return ['message' => 'Impossible to get the array'];
    }

    public function actionItem($request)
    {
        $query = (new Query())->select('id, alias, name')->from('tg_item')->where('alias=:alias', [':alias' => $request]);
        $items = $query->createCommand()->queryAll();

        if (empty($items)) {
            $query->orWhere(['like', 'name', $request]);
            $items = $query->createCommand()->queryAll();
        }

        if (!empty($items)) {
            for ($i = count($items); 0 < $i; --$i) {
                $price = (new Query())->select('count(*) as count, min(price_sell/stuck) as min, avg(price_sell/stuck) as avg, max(price_sell/stuck) as max')->from('tg_shop_good')->where('item_id=:id', [':id' => $items[$i - 1]["id"]])->one();

                $items[$i - 1] = [
                    'id' => $items[$i - 1]["alias"],
                    'name' => $items[$i - 1]["name"],
                    'description' => null,
                    'in_shop' => $price
                ];
            }
            return $items;
        }

        Yii::$app->response->statusCode = 404;
        return ['message' => 'Nothing found'];
    }

    public function actionPrice($request)
    {
        $query = (new Query())->select('id, alias, name')->from('tg_item')->where('alias=:alias', [':alias' => $request]);
        $items = $query->createCommand()->queryAll();

        if (empty($items)) {
            $query->orWhere(['like', 'name', $request]);
            $items = $query->createCommand()->queryAll();
        }

        if (!empty($items)) {
            for ($i = 0, $size = count($items); $i < $size; ++$i) {
                $prices = (new Query())->select('shop_id, price_sell, price_buy, stuck')->from('tg_shop_good')->where('item_id=:id', [':id' => $items[$i]["id"]])->orderBy('IFNULL(`price_sell`, \'1\') / `stuck` ASC')->createCommand()->queryAll();

                if (!empty($prices)) {
                    for ($i_p = 0, $size_p = count($prices); $i_p < $size_p; ++$i_p) {

                        $shop = (new Query())->select('alias, name, logo_url')->from('tg_shop')->where('id=:id', [':id' => $prices[$i_p]["shop_id"]])->one();

                        $shop["logo_url"] = ($shop["logo_url"] == null) ? null : (Url::base(true) . '/images/shop/' . $shop["logo_url"]);
                        $shop["shop_url"] = (Url::base(true) . '/shop/' . $shop["alias"]);
                        unset($shop["alias"]);

                        $prices[$i_p] = [
                            'price_sell' => $prices[$i_p]["price_sell"],
                            'price_buy' => $prices[$i_p]["price_buy"],
                            'stuck' => $prices[$i_p]["stuck"],
                            'shop' => $shop
                        ];
                    }
                }

                $items[$i] = [
                    'id' => $items[$i]["alias"],
                    'name' => $items[$i]["name"],
                    'description' => null,
                    'image_url' => Url::base(true) . '/images/items/' . $items[$i]["alias"] . '.png',
                    'in_shop' => $prices
                ];
            }

            return (count($items) === 1) ? $items[0] : $items;
        }

        Yii::$app->response->statusCode = 404;
        return ['message' => 'Nothing found'];
    }
}
