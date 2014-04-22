<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\helpers\ParseHTML;
use app\models\Item;
use app\models\Price;
use app\models\Shop;

class ParserController extends Controller
{
    public function actionTor()
    {
        $url = 'http://cubovo.strana.de/index.html';
        $id_shop = 44; // Тор
        $shop = Shop::findOne($id_shop);

        $grid = [];
        $html = new ParseHTML(file_get_contents($url));
        $tables = $html->get('table tbody')->toArray();

        foreach($tables as $table)
        {
            unset($table["tr"][0]);
            foreach($table["tr"] as $tr)
            {
                unset($tr["#text"]);
                unset($tr["td"][0]);

                $item = Item::findByName($tr["td"][1]["#text"]);
                $price_sell = str_replace(".", "", str_replace("-", "—", $tr["td"][3]["#text"]));
                $price_buy = str_replace(".", "", str_replace("-", "—", $tr["td"][4]["#text"]));
                $stuck = $tr["td"][2]["#text"];

                $status = Price::addPrice($item->id, $id_shop, $price_sell, $price_buy, $stuck);
                $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => $status];
            }
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }

    public function actionCubovo()
    {
        $url = 'http://cubovo.strana.de/index_per.html';
        $id_shop = 45; // Перекресток
        $shop = Shop::findOne($id_shop);

        $grid = [];
        $html = new ParseHTML(file_get_contents($url));
        $tables = $html->get('table tbody')->toArray();

        foreach($tables as $table)
        {
            unset($table["tr"][0]);
            foreach($table["tr"] as $tr)
            {
                unset($tr["#text"]);
                unset($tr["td"][0]);
                unset($tr["th"]);

                if(isset($tr["td"][1]["#text"]))
                {
                $item = Item::findByName($tr["td"][1]["#text"]);
                $price_sell = str_replace(".", "", str_replace("-", "—", $tr["td"][3]["#text"]));
                $price_buy = str_replace(".", "", str_replace("-", "—", $tr["td"][4]["#text"]));
                $stuck = $tr["td"][2]["#text"];

                $status = Price::addPrice($item->id, $id_shop, $price_sell, $price_buy, $stuck);
                $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => $status];
                }
            }
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }

    public function actionMagazin()
    {
        $url = 'http://romashkax.valuehost.ru/shop.php';
        $id_shop = 48; // Магазин "Магазин"
        $shop = Shop::findOne($id_shop);

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

                $status = Price::addPrice($item->id, $id_shop, $price_sell, $price_buy, $stuck);
                $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => $status];
            }
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }

    public function actionKaktyc()
    {
        $url = 'http://kaktyc.ovesnovs.com/';
        $id_shop = 4; // Кактус
        $shop = Shop::findOne($id_shop);

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

            $status = Price::addPrice($item->id, $id_shop, $price_sell, $price_buy, $stuck);
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
        $id_shop = 53; // Кактус
        $shop = Shop::findOne($id_shop);

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

            $status = Price::addPrice($item->id, $id_shop, $price_sell, $price_buy, $stuck);
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
        $id_shop = 42; // Кактус
        $shop = Shop::findOne($id_shop);

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

            $status = Price::addPrice($item->id, $id_shop, $price_sell, $price_buy, $stuck);
            $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => $status];
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }

    public function actionNshop()
    {
        $url = 'http://normalshop.ru/';
        $id_shop = 46; // Кактус
        $shop = Shop::findOne($id_shop);

        $grid = [];
        $html = new ParseHTML(str_replace("\xC2\xA0", "", file_get_contents($url)));
        $tables = $html->get('table tbody')->toArray();

        foreach($tables as $table)
        {
            unset($table["tr"][0]);
            unset($table["tr"][1]);
            foreach($table["tr"] as $tr)
            {
                unset($tr["td"][0]);
                unset($tr["#text"]);

                $item = Item::findByName($tr["td"][1]["#text"]);
                $price_sell = str_replace(" ", "", str_replace("-", "—", $tr["td"][3]["#text"]));
                $price_buy = str_replace(" ", "", str_replace("-", "—", $tr["td"][4]["#text"]));
                $stuck = $tr["td"][2]["#text"];

                $status = Price::addPrice($item->id, $id_shop, $price_sell, $price_buy, $stuck);
                $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => $status];
            }
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }

    public function actionIshop()
    {
        $url = 'http://ishop-gc.ru/';
        $id_shop = 54; // Кактус
        $shop = Shop::findOne($id_shop);

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
                    $price_sell = $tr["td"][3]["p"][0]["span"]["#text"];
                    $price_buy = $tr["td"][4]["p"][0]["span"]["#text"];

                    $item = Item::findByName($name);
                    $status = Price::addPrice($item->id, $id_shop, $price_sell, $price_buy, $stuck);
                    $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => $status];
                }
            }
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }

    public function actionTwix()
    {
        $url = file_get_contents('http://www.macmax.ru/twix/price/twix/');
        $id_shop = 1; // Twix
        $shop = Shop::findOne($id_shop);

        $grid = [];
        $table = json_decode($url);

        foreach($table->price as $line)
        {
            $alias = str_replace(",", ".", str_replace(",0", "", $line->item_id));
            $price_sell = '—';
            $price_buy = '—';
            $stuck = 0;
            foreach($line->item_price_out as $price)
            {
                $price_sell = $price->p_out;
                $stuck = $price->q_out;
            }
            foreach($line->item_price_in as $price)
            {
                if($price->q_in == $stuck)
                    $price_buy = $price->p_in;
                else if($stuck != 0)
                    $price_buy = round(($price->p_in/$price->q_in)*$stuck);
                else
                {
                    $price_buy = $price->p_in;
                    $stuck = $price->q_in;
                }
            }
            $item = Item::findByAlias($alias);
            $status = Price::addPrice($item->id, $id_shop, $price_sell, $price_buy, $stuck);
            $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => $status];
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }

    public function actionSvgrad()
    {
        $url = file_get_contents('http://www.macmax.ru/twix/price/sg/');
        $id_shop = 3; // Svgrad
        $shop = Shop::findOne($id_shop);

        $grid = [];
        $table = json_decode($url);

        foreach($table->price as $line)
        {
            $alias = str_replace(",", ".", str_replace(",0", "", $line->item_id));
            $price_sell = '—';
            $price_buy = '—';
            $stuck = 0;
            foreach($line->item_price_out as $price)
            {
                $price_sell = $price->p_out;
                $stuck = $price->q_out;
            }
            foreach($line->item_price_in as $price)
            {
                if($price->q_in == $stuck)
                    $price_buy = $price->p_in;
                else if($stuck != 0)
                    $price_buy = round(($price->p_in/$price->q_in)*$stuck);
                else
                {
                    $price_buy = $price->p_in;
                    $stuck = $price->q_in;
                }
            }
            $item = Item::findByAlias($alias);
            $status = Price::addPrice($item->id, $id_shop, $price_sell, $price_buy, $stuck);
            $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => $status];
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop->name,
        ]);
    }
}
