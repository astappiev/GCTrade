<?php
namespace app\modules\shop\controllers;

use Yii;
use yii\web\Controller;
use app\helpers\ParseHTML;
use app\modules\shop\models\Item;
use app\modules\shop\models\Good;
use app\modules\shop\models\Shop;

class ParserController extends Controller
{
    public function actionTorCubovo()
    {
        $url = 'http://cubovo.strana.de/';
        $shop_tor = Shop::findOne(44); // Тор
        $shop_cubovo = Shop::findOne(45); // Перекресток
        Good::deleteAll(['shop_id' => [$shop_tor->id, $shop_cubovo->id]]);

        $html = new ParseHTML(file_get_contents($url));
        $grid = [];

        $tables = $html->get('table')->toArray();
        foreach($tables as $table)
        {
            $trs = isset($table["tbody"]) ? $table["tbody"]["tr"] : $table["tr"];
            if (isset($trs[0]["th"])) unset($trs[0]);
            foreach($trs as $tr)
            {
                $name = $tr["td"][1]["#text"];
                $isTor = $tr["td"][2]["#text"] == '-' ? 0 : 1;
                $isCobovo = $tr["td"][3]["#text"] == '-' ? 0 : 1;
                $stuck = $tr["td"][4]["#text"];
                $price_sell = str_replace(".", "", $tr["td"][6]["#text"]);
                $price_buy = str_replace(".", "", $tr["td"][5]["#text"]);

                $price_sell = $price_sell == '-' ? null : $price_sell;
                $price_buy = $price_buy == '-' ? null : $price_buy;

                $item = Item::findByName($name);

                if(!$item) return 'Not item: '.$name;

                if($isTor) {
                    $price = new Good();
                    $price->shop_id = $shop_tor->id;
                    $price->item_id = $item->id;
                    $price->price_buy = $price_buy;
                    $price->price_sell = $price_sell;
                    $price->stuck = $stuck;
                    if($price->save()) {
                        $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => '+ Tor'];
                    } else {
                        return 'Not save (buy) ' . $item->alias . ' ('.$name.') -> ' . $price_sell . ':' .$price_buy . ' kol: ' . $stuck;
                    }
                }

                if($isCobovo) {
                    $price = new Good();
                    $price->shop_id = $shop_cubovo->id;
                    $price->item_id = $item->id;
                    $price->price_buy = $price_buy;
                    $price->price_sell = $price_sell;
                    $price->stuck = $stuck;
                    if($price->save()) {
                        $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => '+ Cubovo'];
                    } else {
                        return 'Not save';
                    }
                }
            }
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop_tor->name.' - '.$shop_cubovo->name,
        ]);
    }

    /* Прайс заброшен.
    public function actionMagazin()
    {
        $url = 'http://romashkax.valuehost.ru/shop.php';
        $shop_id = 48; // Магазин "Магазин"
        $shop = Shop::findOne($shop_id);

        $grid = [];
        $html = new ParseHTML(file_get_contents($url));
        $table = $html->get('table.price')->toArray();

        unset($table[0]["tr"][0]);
        foreach($table[0]["tr"] as $tr)
        {
            if(count($tr["td"])>3)
            {
                $item = Item::findByName($tr["td"][1]["#text"]);
                $price_sell = (isset($tr["td"][3]["#text"]))?$tr["td"][3]["#text"]:NULL;
                $price_buy = (isset($tr["td"][4]["#text"]))?$tr["td"][4]["#text"]:NULL;
                $stuck = $tr["td"][2]["#text"];

                $status = Good::addPrice($item->id, $shop_id, $price_sell, $price_buy, $stuck);
                $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => $status];
            }
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }*/

    /*public function actionKaktyc()
    {
        $url = 'http://kaktyc.ovesnovs.com/';
        $shop_id = 4; // Кактус
        $shop = Shop::findOne($shop_id);

        $grid = [];
        $html = new ParseHTML(file_get_contents($url));
        $table = $html->get('table.maintable')->toArray();

        unset($table[0]["tr"][0]);
        foreach($table[0]["tr"] as $tr)
        {
            unset($tr["td"][0]);

            $item = Item::findByName($tr["td"][1]["#text"]);
            $price_sell = (is_numeric($tr["td"][3]["#text"]))?$tr["td"][3]["#text"]:NULL;
            $price_buy = (is_numeric($tr["td"][4]["#text"]))?$tr["td"][4]["#text"]:NULL;
            $stuck = $tr["td"][2]["#text"];

            $status = Good::addPrice($item->id, $shop_id, $price_sell, $price_buy, $stuck);
            $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => $status];
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }

    public function actionUnderlake()
    {
        $url = 'http://kaktyc.ovesnovs.com/underlake.htm';
        $shop_id = 53; // Кактус
        $shop = Shop::findOne($shop_id);

        $grid = [];
        $html = new ParseHTML(file_get_contents($url));
        $table = $html->get('table.maintable')->toArray();

        unset($table[0]["tr"][0]);
        foreach($table[0]["tr"] as $tr)
        {
            unset($tr["td"][0]);

            $item = Item::findByName($tr["td"][1]["#text"]);
            $price_sell = (is_numeric($tr["td"][3]["#text"]))?$tr["td"][3]["#text"]:NULL;
            $price_buy = (is_numeric($tr["td"][4]["#text"]))?$tr["td"][4]["#text"]:NULL;
            $stuck = $tr["td"][2]["#text"];

            $status = Good::addPrice($item->id, $shop_id, $price_sell, $price_buy, $stuck);
            $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => $status];
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }

    public function actionJib()
    {
        $url = 'http://kaktyc.ovesnovs.com/jib.htm';
        $shop_id = 42; // Кактус
        $shop = Shop::findOne($shop_id);

        $grid = [];
        $html = new ParseHTML(file_get_contents($url));
        $table = $html->get('table.maintable')->toArray();

        unset($table[0]["tr"][0]);
        foreach($table[0]["tr"] as $tr)
        {
            unset($tr["td"][0]);

            $item = Item::findByName($tr["td"][1]["#text"]);
            $price_sell = (is_numeric($tr["td"][3]["#text"]))?$tr["td"][3]["#text"]:NULL;
            $price_buy = (is_numeric($tr["td"][4]["#text"]))?$tr["td"][4]["#text"]:NULL;
            $stuck = $tr["td"][2]["#text"];

            $status = Good::addPrice($item->id, $shop_id, $price_sell, $price_buy, $stuck);
            $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => $status];
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }*/

    /*public function actionIshop()
    {
        $url = 'http://ishop-gc.ru/';
        $shop_id = 54;
        $shop = Shop::findOne($shop_id);

        $grid = [];
        $html = new ParseHTML(file_get_contents($url));
        $tables = $html->get('table tbody')->toArray();

        foreach($tables as $table)
        {
            foreach($table["tr"] as $tr)
            {
                if(!$tr["td"][0]["colspan"])
                {
                    $name = ($tr["td"][1]["span"]["#text"])?$tr["td"][1]["span"]["#text"]:$tr["td"][1]["p"][0]["span"]["#text"];
                    $stuck = str_replace(" x", "", $tr["td"][2]["p"][0]["span"]["#text"]);
                    $price_sell = intval($tr["td"][3]["p"][0]["span"]["#text"][0]) . intval($tr["td"][3]["p"][0]["span"]["span"][0]["#text"]);
                    $price_buy = intval($tr["td"][4]["p"][0]["span"]["#text"][0]) . intval($tr["td"][4]["p"][0]["span"]["span"][0]["#text"]);

                    $item = Item::findByName($name);
                    if(!$item) return 'Not item: \''.$name.'\'';

                    $price = new Good();
                    $price->shop_id = $shop->id;
                    $price->item_id = $item->id;
                    $price->price_buy = $price_buy;
                    $price->price_sell = $price_sell;
                    $price->stuck = $stuck;
                    if($price->save()) {
                        $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => '+'];
                    } else {
                        return 'Not save ' . $item->alias . ' -> ' .$price_sell . ':' . $price_buy . ', col: ' . $stuck;
                    }
                }
            }
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }*/

    public function actionTwix()
    {
        $url = file_get_contents('http://www.macmax.ru/twix/price/twix/');
        $shop = Shop::findOne(1); // Twix
        Good::deleteAll(['shop_id' => $shop->id]);
        $grid = [];

        $table = json_decode($url);
        foreach($table->price as $line)
        {
            $id = explode(",", $line->item_id);
            $item = Item::find()->where(["id_primary" => $id[0], "id_meta" => $id[1]])->one();

            $price_sell = $line->item_price_out->p_out;
            $stuck_sell = $line->item_price_out->q_out;

            $price_buy = $line->item_price_in->p_in;
            $stuck_buy = $line->item_price_in->q_in;

            if($stuck_sell === $stuck_buy)
            {
                $price = new Good();
                $price->shop_id = $shop->id;
                $price->item_id = $item->id;
                $price->price_buy = $price_buy;
                $price->price_sell = $price_sell;
                $price->stuck = $stuck_sell;
                if($price->save()) {
                    $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck_sell, 'status' => '+'];
                } else {
                    return 'Not save';
                }
            } else {
                if($price_buy !== null)
                {
                    $price = new Good();
                    $price->shop_id = $shop->id;
                    $price->item_id = $item->id;
                    $price->price_buy = $price_buy;
                    $price->price_sell = null;
                    $price->stuck = $stuck_buy;
                    if($price->validate() && $price->save()) {
                        $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck_sell, 'status' => '+'];
                    } else {
                        return 'Not save (buy) ' . $item->alias . ' -> ' .$price_buy . ' ' . $stuck_buy;
                    }
                }

                if($price_sell !== null) {
                    $price = new Good();
                    $price->shop_id = $shop->id;
                    $price->item_id = $item->id;
                    $price->price_buy = null;
                    $price->price_sell = $price_sell;
                    $price->stuck = $stuck_sell;
                    if($price->validate() && $price->save()) {
                        $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck_sell, 'status' => '+'];
                    } else {
                        return 'Not save (sell) ' . $item->alias . ' -> ' .$price_sell . ' ' . $stuck_sell;
                    }
                }
            }
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }

    public function actionSvgrad()
    {
        $url = file_get_contents('http://www.macmax.ru/twix/price/sg/');
        $shop = Shop::findOne(3); // Svgrad
        Good::deleteAll(['shop_id' => $shop->id]);
        $grid = [];

        $table = json_decode($url);
        foreach($table->price as $line)
        {
            $id = explode(",", $line->item_id);
            $item = Item::find()->where(["id_primary" => $id[0], "id_meta" => $id[1]])->one();

            $price_sell = $line->item_price_out->p_out;
            $stuck_sell = $line->item_price_out->q_out;

            $price_buy = $line->item_price_in->p_in;
            $stuck_buy = $line->item_price_in->q_in;

            if($stuck_sell === $stuck_buy)
            {
                $price = new Good();
                $price->shop_id = $shop->id;
                $price->item_id = $item->id;
                $price->price_buy = $price_buy;
                $price->price_sell = $price_sell;
                $price->stuck = $stuck_sell;
                if($price->save()) {
                    $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck_sell, 'status' => '+'];
                } else {
                    return 'Not save';
                }
            } else {
                if($price_buy !== null)
                {
                    $price = new Good();
                    $price->shop_id = $shop->id;
                    $price->item_id = $item->id;
                    $price->price_buy = $price_buy;
                    $price->price_sell = null;
                    $price->stuck = $stuck_buy;
                    if($price->validate() && $price->save()) {
                        $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck_sell, 'status' => '+'];
                    } else {
                        return 'Not save (buy) ' . $item->alias . ' -> ' .$price_buy . ' ' . $stuck_buy;
                    }
                }

                if($price_sell !== null) {
                    $price = new Good();
                    $price->shop_id = $shop->id;
                    $price->item_id = $item->id;
                    $price->price_buy = null;
                    $price->price_sell = $price_sell;
                    $price->stuck = $stuck_sell;
                    if($price->validate() && $price->save()) {
                        $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck_sell, 'status' => '+'];
                    } else {
                        return 'Not save (sell) ' . $item->alias . ' -> ' .$price_sell . ' ' . $stuck_sell;
                    }
                }
            }
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }
}
