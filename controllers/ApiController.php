<?php
namespace app\controllers;

use Yii;
use yii\helpers\Url;
use linslin\yii2\curl\Curl;
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

    /**
     * Request for get shop with alias equivalent $request
     * Use: http://gctrade.ru/api/shops/:alias
     * Example: http://gctrade.ru/api/shops/nottingham
     * ```json
     * {
     *      "alias": "nottingham",
     *      "type": "0",
     *      "name": "ТЦ Ноттингем",
     *      "about": "Магазин строительных материалов.",
     *      "description": "<p>Администратор магазина Aceko.</p><p>При возникновении проблем пишите  в игре.</p><p>При желании купить оптом \"Белый Камень\", \"Камень\", \"Бревна\"(всех видов), красителей пишите в ЛС.</p>",
     *      "subway": "Nott",
     *      "x_cord": "-7700",
     *      "z_cord": "-1750",
     *      "image_url": "http://gctrade.ru/images/shop/nottingham_l0cmw.jpg",
     *      "updated_at": "1421590259"
     * }
     * ```
     * @param $request string shop alias
     * @return array one shop
     */
	public function actionShop($request)
	{
        $row = (new Query)->select('alias, type, name, about, description, subway, x_cord, z_cord, image_url, updated_at')->from('tg_shop')->where(['alias' => $request])->one();

        if (!empty($row)) {
            $row["image_url"] = Url::base(true) . ($row["image_url"] == null ? '/images/nologo.png' : '/images/shop/' . $row["image_url"]);
            return $row;
        } else {
            Yii::$app->response->statusCode = 404;
            return ['message' => 'Shop "' . $request .'" not found'];
        }
	}

    /**
     * Get all shops (short)
     * Use: http://gctrade.ru/api/shops
     * Example: http://gctrade.ru/api/shops
     * ```json
     * [
     *      {
     *          "alias": "twix",
     *          "type": "0",
     *          "name": "Гипермаркет TWIX",
     *          "about": "В наших магазинах вы найдете самые разные товары на всякий вкус и достаток, причем иногда на выбор разной цены или количества. Казино порадует вас разнообразием ставок и ценными призами.",
     *          "x_cord": "-7675",
     *          "z_cord": "-915",
     *          "image_url": "http://gctrade.ru/images/shop/twix_Atnkhh.png",
     *          "updated_at": "1421590259"
     *      },
     *      {
     *          "alias": "nottingham",
     *          "type": "0",
     *          "name": "ТЦ Ноттингем",
     *          "about": "Магазин строительных материалов.",
     *          "x_cord": "-7700",
     *          "z_cord": "-1750",
     *          "image_url": "http://gctrade.ru/images/shop/nottingham_l0cmw.jpg",
     *          "updated_at": "1421590259"
     *      },
     *      ...
     * ]
     * ```
     *
     * Get all shops from request
     * Use with search: http://gctrade.ru/api/shop/search/:query (name or alias for search)
     * Example with search: http://gctrade.ru/api/shop/search/twix
     * ```json
     * [
     *      {
     *          "alias": "twix",
     *          "type": "0",
     *          "name": "Гипермаркет TWIX",
     *          "about": "В наших магазинах вы найдете самые разные товары на всякий вкус и достаток, причем иногда на выбор разной цены или количества. Казино порадует вас разнообразием ставок и ценными призами.",
     *          "x_cord": "-7675",
     *          "z_cord": "-915",
     *          "image_url": "http://gctrade.ru/images/shop/twix_Atnkhh.png",
     *          "updated_at": "1421590259"
     *      }
     * ]
     * ```
     *
     * @param $request string
     * @return array shops
     */
    function actionShopSearch($request = null)
	{
        $query = (new Query)->select('alias, type, name, about, x_cord, z_cord, image_url, updated_at')->from('tg_shop');
        if ($request) {
            $query->where(['alias' => $request]);
        }
        $rows = $query->createCommand()->queryAll();

        if (empty($rows)) {
            $query->orWhere(['like', 'name', $request]);
            $rows = $query->createCommand()->queryAll();
        }

        if (!empty($rows)) {
            for ($i = 0, $length = count($rows); $i < $length; ++$i) {
                $rows[$i]["image_url"] = Url::base(true) . ($rows[$i]["image_url"] == null ? '/images/nologo.png' : '/images/shop/' . $rows[$i]["image_url"]);
            }
            return $rows;
        } else {
            Yii::$app->response->statusCode = 404;
            return ['message' => 'Nothing found'];
        }
	}

    /**
     * Request for get item with id equivalent $request
     * Use: http://gctrade.ru/api/items/:id
     * Example: http://gctrade.ru/api/items/1
     * ```json
     * {
     *      "id": "1",
     *      "name": "Камень",
     *      "image_url": "http://gctrade.ru/images/items/1.png"
     * }
     * ```
     * @param $request string shop alias
     * @return array one shop
     */
    public function actionItem($request)
    {
        $row = (new Query)->select('alias as id, name')->from('tg_item')->where(['alias' => $request])->one();

        if (!empty($row)) {
            $row["image_url"] = Url::base(true) . '/images/items/' . $row["id"] . '.png';
            return $row;
        } else {
            Yii::$app->response->statusCode = 404;
            return ['message' => 'Item "' . $request .'" not found'];
        }
    }

    /**
     * Get all items
     * Use: http://gctrade.ru/api/items
     * Example: http://gctrade.ru/api/items
     * ```json
     * [
     *      {
     *          "id": "1",
     *          "name": "Камень",
     *          "image_url": "http://gctrade.ru/images/items/1.png"
     *      },
     *      {
     *          "id": "2",
     *          "name": "Трава",
     *          "image_url": "http://gctrade.ru/images/items/2.png"
     *      }
     *      ...
     * ]
     * ```
     *
     * Get all items from request
     * Use with search: http://gctrade.ru/api/item/search/:name (name or id for search)
     * Example with search: http://gctrade.ru/api/item/search/камень
     * ```json
     * [
     *      {
     *          "id": "1",
     *          "name": "Камень",
     *          "image_url": "http://gctrade.ru/images/items/1.png"
     *      }
     * ]
     * ```
     *
     * @param $request string name or id
     * @return array items
     */
    public function actionItemSearch($request = null)
    {
        $query = (new Query)->select('alias as id, name')->from('tg_item');
        if ($request) {
            $query->where(['alias' => $request]);
        }
        $rows = $query->createCommand()->queryAll();

        if (empty($rows)) {
            $query->orWhere(['like', 'name', $request]);
            $rows = $query->createCommand()->queryAll();
        }

        if (!empty($rows)) {
            for ($i = 0, $length = count($rows); $i < $length; ++$i) {
                $rows[$i]["image_url"] = Url::base(true) . '/images/items/' . $rows[$i]["id"] . '.png';
            }
            return $rows;
        } else {
            Yii::$app->response->statusCode = 404;
            return ['message' => 'Nothing found'];
        }
    }

    /**
     * Get all cost of items
     * Use: http://gctrade.ru/api/item/cost
     * Example: http://gctrade.ru/api/item/cost
     * ```json
     * [
     *      {
     *          "id": "1",
     *          "name": "Камень",
     *          "image_url": "http://gctrade.ru/images/items/1.png"
     *          "in_shop": {
     *              "count": "20",
     *              "min": "0.7031",
     *              "avg": "1.12265625",
     *              "max": "1.2500"
     *          }
     *      },
     *      {
     *          "id": "2",
     *          "name": "Трава",
     *          "image_url": "http://gctrade.ru/images/items/2.png"
     *          "in_shop": {
     *              "count": "20",
     *              "min": "0.7031",
     *              "avg": "1.12265625",
     *              "max": "1.2500"
     *          }
     *      }
     *      ...
     * ]
     * ```
     *
     * Get all cost of item from request
     * Use with search: http://gctrade.ru/api/item/cost/:name (name or id for search)
     * Example with search: http://gctrade.ru/api/item/cost/камень
     * ```json
     * [
     *      {
     *          "id": "1",
     *          "name": "Камень",
     *          "image_url": "http://gctrade.ru/images/items/1.png"
     *          "in_shop": {
     *              "count": "6",
     *              "min": "8000.0000",
     *              "avg": "9016.66666667",
     *              "max": "10000.0000"
     *          }
     *      }
     * ]
     * ```
     *
     * @param $request string name or id
     * @return array items
     */
    public function actionItemCost($request = null)
    {
        $query = (new Query)->select('id, alias, name')->from('tg_item');
        if ($request) {
            $query->where(['alias' => $request]);
        }
        $items = $query->createCommand()->queryAll();

        if (empty($items)) {
            $query->orWhere(['like', 'name', $request]);
            $items = $query->createCommand()->queryAll();
        }

        if (!empty($items)) {
            for ($i = 0, $length = count($items); $i < $length; ++$i) {
                $price = (new Query())->select('count(*) as count, min(price_sell/stuck) as min, avg(price_sell/stuck) as avg, max(price_sell/stuck) as max')->from('tg_shop_good')->where(['item_id' => $items[$i]["id"]])->one();
                $items[$i] = [
                    'id' => $items[$i]["alias"],
                    'name' => $items[$i]["name"],
                    'image_url' => Url::base(true) . '/images/items/' . $items[$i]["id"] . '.png',
                    'in_shop' => $price
                ];
            }
            return $items;
        } else {
            Yii::$app->response->statusCode = 404;
            return ['message' => 'Nothing found'];
        }
    }

    /**
     * Request for get goods with id equivalent $request
     * Use: http://gctrade.ru/api/goods/:id
     * Example: http://gctrade.ru/api/goods/1
     * ```json
     * {
     *      "id": "1",
     *      "name": "Камень",
     *      "image_url": "http://gctrade.ru/images/items/1.png"
     *      "in_shop": [
     *          {
     *              "price_sell": "45",
     *              "price_buy": "20",
     *              "stuck": "64",
     *              "shop": {
     *                  "name": "Зелёный Гоблин",
     *                  "image_url": "http://gctrade.ru/images/shop/546552327400f.png",
     *                  "shop_url": "http://gctrade.ru/shop/GreenGoblin"}
     *              }
     *          },
     *          {
     *              "price_sell": "48",
     *              "price_buy": "30",
     *              "stuck": "64",
     *              "shop": {
     *              "name": "ТЦ Ноттингем",
     *                  "image_url": "http://gctrade.ru/images/shop/nottingham_l0cmw.jpg",
     *                  "shop_url":"http://gctrade.ru/shop/nottingham"
     *              }
     *          },
     *          ...
     *      ]
     * }
     * ```
     * @param $request string item id
     * @return array one item with goods
     */
    public function actionGoods($request)
    {
        // TODO: Проверить будет ли запрос быстрее если записать одним целым
        $item = (new Query)->select('id, alias, name')->from('tg_item')->where(['alias' => $request])->one();

        if (!empty($item)) {
            $prices = (new Query())->select('shop_id, price_sell, price_buy, stuck')->from('tg_shop_good')->where(['item_id' => $item["id"]])->orderBy('IFNULL(`price_sell`, \'1\') / `stuck` ASC')->all();

            if (!empty($prices)) {
                for ($i = 0, $length = count($prices); $i < $length; ++$i) {
                    $shop = (new Query())->select('alias, name, image_url')->from('tg_shop')->where(['id' => $prices[$i]["shop_id"]])->one();
                    $prices[$i] = [
                        'price_sell' => $prices[$i]["price_sell"],
                        'price_buy' => $prices[$i]["price_buy"],
                        'stuck' => $prices[$i]["stuck"],
                        'shop' => [
                            'name' => $shop["name"],
                            'image_url' => Url::base(true) . ($shop["image_url"] == null ? '/images/nologo.png' : '/images/shop/' . $shop["image_url"]),
                            'shop_url' => Url::base(true) . '/shop/' . $shop["alias"]
                        ]
                    ];
                }
            }

            return [
                'id' => $item["alias"],
                'name' => $item["name"],
                'image_url' => Url::base(true) . '/images/items/' . $item["id"] . '.png',
                'in_shop' => $prices
            ];
        } else {
            Yii::$app->response->statusCode = 404;
            return ['message' => 'Goods "' . $request .'" not found'];
        }
    }

    /**
     * Get all goods
     * Use: http://gctrade.ru/api/goods
     * Example: http://gctrade.ru/api/goods
     * ```json
     * [
     *      {
     *          "id": "1",
     *          "name": "Камень",
     *          "image_url": "http://gctrade.ru/images/items/1.png"
     *          "in_shop": [
     *              {
     *                  "price_sell": "45",
     *                  "price_buy": "20",
     *                  "stuck": "64",
     *                  "shop": {
     *                      "name": "Зелёный Гоблин",
     *                      "image_url": "http://gctrade.ru/images/shop/546552327400f.png",
     *                      "shop_url": "http://gctrade.ru/shop/GreenGoblin"}
     *                  }
     *              },
     *              {
     *                  "price_sell": "48",
     *                  "price_buy": "30",
     *                  "stuck": "64",
     *                  "shop": {
     *                      "name": "ТЦ Ноттингем",
     *                      "image_url": "http://gctrade.ru/images/shop/nottingham_l0cmw.jpg",
     *                      "shop_url":"http://gctrade.ru/shop/nottingham"
     *                  }
     *              },
     *              ...
     *          ]
     *      },
     *      ...
     * ]
     * ```
     *
     * Get all goods from request
     * Use with search: http://gctrade.ru/api/goods/search/:name (name or id for search)
     * Example with search: http://gctrade.ru/api/goods/search/камень
     * ```json
     * [
     *      {
     *          "id": "1",
     *          "name": "Камень",
     *          "image_url": "http://gctrade.ru/images/items/1.png"
     *          "in_shop": [
     *              {
     *                  "price_sell": "45",
     *                  "price_buy": "20",
     *                  "stuck": "64",
     *                  "shop": {
     *                      "name": "Зелёный Гоблин",
     *                      "image_url": "http://gctrade.ru/images/shop/546552327400f.png",
     *                      "shop_url": "http://gctrade.ru/shop/GreenGoblin"}
     *                  }
     *              },
     *              {
     *                  "price_sell": "48",
     *                  "price_buy": "30",
     *                  "stuck": "64",
     *                  "shop": {
     *                      "name": "ТЦ Ноттингем",
     *                      "image_url": "http://gctrade.ru/images/shop/nottingham_l0cmw.jpg",
     *                      "shop_url":"http://gctrade.ru/shop/nottingham"
     *                  }
     *              },
     *              ...
     *          ]
     *      }
     * ]
     * ```
     *
     * @param $request string name or id
     * @return array goods
     */
    public function actionGoodsSearch($request = null)
    {
        $query = (new Query)->select('id, alias, name')->from('tg_item');
        if ($request) {
            $query->where(['alias' => $request]);
        }
        $items = $query->createCommand()->queryAll();

        if (empty($items)) {
            $query->orWhere(['like', 'name', $request]);
            $items = $query->createCommand()->queryAll();
        }

        if (!empty($items)) {
            for ($i = 0, $length = count($items); $i < $length; ++$i) {
                $prices = (new Query())->select('shop_id, price_sell, price_buy, stuck')->from('tg_shop_good')->where(['item_id' => $items[$i]["id"]])->orderBy('IFNULL(`price_sell`, \'1\') / `stuck` ASC')->all();

                if (!empty($prices)) {
                    for ($p = 0, $length_p = count($prices); $p < $length_p; ++$p) {
                        $shop = (new Query())->select('alias, name, image_url')->from('tg_shop')->where(['id' => $prices[$p]["shop_id"]])->one();
                        $prices[$p] = [
                            'price_sell' => $prices[$p]["price_sell"],
                            'price_buy' => $prices[$p]["price_buy"],
                            'stuck' => $prices[$p]["stuck"],
                            'shop' => [
                                'name' => $shop["name"],
                                'image_url' => Url::base(true) . ($shop["image_url"] == null ? '/images/nologo.png' : '/images/shop/' . $shop["image_url"]),
                                'shop_url' => Url::base(true) . '/shop/' . $shop["alias"]
                            ]
                        ];
                    }
                }

                $items[$i] = [
                    'id' => $items[$i]["alias"],
                    'name' => $items[$i]["name"],
                    'image_url' => Url::base(true) . '/images/items/' . $items[$i]["id"] . '.png',
                    'in_shop' => $prices
                ];
            }
            return $items;
        } else {
            Yii::$app->response->statusCode = 404;
            return ['message' => 'Nothing found'];
        }
    }

    /**
     * Get user regions (Current user)
     * Use: http://gctrade.ru/api/user/regions
     * ```json
     * [
     *      {
     *          "name": "GCRC_CrystalCity1",
     *          "rights": [
     *              "full"
     *          ],
     *          "coordinates": {
     *              "first": "-5531 63 190",
     *              "second": "-5524 127 214"
     *          }
     *      },
     *      {
     *          "name": "GCRC_BA-Plaza_owner",
     *          "rights": [
     *              "full"
     *          ],
     *          "coordinates": {
     *              "first": "-8276 65 -906",
     *              "second": "-8053 127 -898"
     *          }
     *      },
     *      ...
     * ]
     * ```
     * @return array
     */
    public function actionUserRegions()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->statusCode = 403;
            return ['message' => 'You must be logged in'];
        }

        $user = \app\modules\users\models\User::findOne([Yii::$app->user->id]);
        if ($user->access_token == null) {
            Yii::$app->response->statusCode = 403;
            return ['message' => 'It is a trouble with accessToken'];
        }

        $curl = new Curl();
        $response = $curl->get('https://api.greencubes.org/user/regions?access_token=' . $user->access_token);
        if ($curl->responseCode === 200) {
            return json_decode($response);
        } else {
            Yii::$app->response->statusCode = 403;
            return ['message' => 'Incorrect request or server is unavailable'];
        }
    }

    /**
     * Get users location
     * Use: http://gctrade.ru/api/user/world
     * Example: http://gctrade.ru/api/user/world
     * ```json
     * [
     *      {
     *          "username": "Aceko",
     *          "coordinates": "-7540 46 -2092"
     *      },
     *      {
     *          "username": "Aleksandr1977",
     *          "coordinates": "-8044 64 -301"
     *      },
     *      ...
     * ]
     * ```
     *
     * Get user location by username
     * Use: http://gctrade.ru/api/user/world/:username
     * Example: http://gctrade.ru/api/user/world/gcmap
     * ```json
     * {
     *      "username": "GCMap",
     *      "coordinates": "-10346 1 -2785"
     * }
     * ```
     * @param $request string username
     * @return array
     */
    public function actionUserWorld($request = null)
    {
        $curl = new Curl();
        $response = $curl->get('http://srv1.greencubes.org/up/world/world');
        if ($curl->responseCode === 200) {
            $players = [];
            if (!empty($response)) {
                $response = json_decode($response)->players;

                foreach ($response as $record) {
                    $row = ['username' => $record->name, 'coordinates' => round($record->x).' '.round($record->y).' '.round($record->z)];
                    if ($request && strtolower($row["username"]) === strtolower($request)) {
                        return $row;
                    }
                    $players[] = $row;
                }

                if ($request) {
                    Yii::$app->response->statusCode = 404;
                    return ['message' => 'User is offline'];
                }
            }
            return $players;
        } else {
            Yii::$app->response->statusCode = 404;
            return ['message' => 'Could not connect to the server'];
        }
    }

    /**
     * Get user head picture
     * Use: http://gctrade.ru/api/user/head/:username
     * Example: http://gctrade.ru/api/user/head/astappev
     * @param $request string username
     * @return array|mixed
     */
    public function actionUserHead($request)
    {
        $curl = new Curl();
        $response = $curl->get('https://api.greencubes.org/users/' . $request);
        if ($curl->responseCode === 200) {
            $src = json_decode($response)->skin_url;
            if ($curl->head($src)) {
                $size = getimagesize($src);
                $src_size = $size[0] / 8;
                $img_size = 32;
                $src_image = imagecreatefrompng($src);
                $image = ImageCreateTrueColor($img_size, $img_size);
                imagecopyresampled($image, $src_image, 0, 0, $src_size, $src_size, $img_size, $img_size, $src_size, $src_size);
                imagecopyresampled($image, $src_image, 0, 0, $src_size * 5, $src_size, $img_size, $img_size, $src_size, $src_size);
            } else {
                $image = imagecreatefromjpeg(Yii::getAlias('@webroot').'/images/head_skin.jpg');
            }

            Header('Content-type: image/jpeg');
            Header('Content-Disposition: filename="' . $request . '.jpg"');
            imagejpeg($image, null, 90);
            return null;
        } else {
            Yii::$app->response->statusCode = 404;
            return ['message' => 'Could not connect to the server'];
        }
    }

    /**
     * Get user badges
     * Use: http://gctrade.ru/api/user/badges/:username
     * Example: http://gctrade.ru/api/user/badges/astappev
     * @param $request string username
     * @return array|mixed
     */
    public function actionUserBadges($request)
    {
        $curl = new Curl();
        $response = $curl->get('https://api.greencubes.org/users/' . $request);
        if ($curl->responseCode === 200) {
            $json = json_decode($response)->badges;

            if (empty($json)) {
                Yii::$app->response->statusCode = 404;
                return ['message' => 'User hasn\'t badges'];
            }

            $width_badges = 32;
            $in_line = 14;
            $margin = 8;
            $line_width = ($width_badges * $in_line) + ($margin * ($in_line - 1));

            $k = 0;
            foreach($json as $badge)
                $k += $badge->count;

            $width = ($k > $in_line) ? $line_width : ($width_badges * $k + (($k - 1) * $margin));
            $height = ceil($k/$in_line) * $width_badges + ((ceil($k/$in_line) - 1) * $margin);
            $current_height = $current_width = 0;
            $img = imagecreatetruecolor($width, $height);
            $black = imagecolorallocate($img, 0, 0, 0);

            imagecolortransparent($img, $black); //прозрачный фон

            foreach ($json as $badge) {
                $id = $badge->badgeData > 0 ? $badge->badgeId.'.'.$badge->badgeData : $badge->badgeId;
                $badge_img = imagecreatefrompng(Yii::getAlias('@webroot').'/images/items/'.$id.'.png');
                imagealphablending($badge_img, false);
                imagesavealpha($badge_img, true);

                for ($i = 0, $length = $badge->count; $i < $length; ++$i) {
                    imagecopy($img, $badge_img, $current_width, $current_height, 0, 0, $width_badges, $width_badges);
                    $current_width += $width_badges + $margin;
                    if ($current_width > $line_width) {
                        $current_width = 0;
                        $current_height += $width_badges + $margin;
                    }
                }
            }

            Header('Content-Type: image/png');
            Header('Content-Disposition: filename="badge_' . $request . '.png"');
            imagepng($img);
            imagedestroy($img);
            return null;
        } else {
            Yii::$app->response->statusCode = 404;
            return ['message' => 'Could not connect to the server'];
        }
    }
}
