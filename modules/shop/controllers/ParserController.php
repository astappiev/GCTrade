<?php
namespace app\modules\shop\controllers;

use Yii;
use yii\web\Controller;
use app\helpers\ParseHTML;
use app\modules\shop\models\Item;
use app\modules\shop\models\Price;
use app\modules\shop\models\Shop;

class ParserController extends Controller
{
    public function actionTorCubovo()
    {
        $url = 'http://cubovo.strana.de/';
        $id_shop_tor = 44; // Тор
        $id_shop = 45; // Перекресток
        $shop_tor = Shop::findOne($id_shop_tor);
        $shop = Shop::findOne($id_shop);

        $grid = [];
        $html = new ParseHTML(file_get_contents($url));
        $tables = $html->get('table')->toArray();

        foreach($tables as $table)
        {
            $table = (isset($table["tbody"]))?$table["tbody"]["tr"]:$table["tr"];
            if(isset($table[0]["th"])) unset($table[0]);
            foreach($table as $tr)
            {
                if(isset($tr["#text"])) unset($tr["#text"]);

                $name = $tr["td"][1]["#text"];
                $isCubovo = ($tr["td"][2]["#text"] == '-')?0:1;
                $isTor = ($tr["td"][3]["#text"] == '-')?0:1;
                $stuck = $tr["td"][4]["#text"];
                $price_sell = str_replace(".", "", str_replace("-", "—", $tr["td"][6]["#text"]));
                $price_buy = str_replace(".", "", str_replace("-", "—", $tr["td"][5]["#text"]));

                $item = Item::findByName($name);

                if(!$item)
                {
                    echo $name;
                    return;
                }

                $status = '';
                if($isCubovo)
                    $status .= Price::addPrice($item->id, $id_shop, $price_sell, $price_buy, $stuck);
                if($isTor)
                    $status .= Price::addPrice($item->id, $id_shop_tor, $price_sell, $price_buy, $stuck);
                $grid[] = ['id' => $item->alias, 'name' => $item->name, 'price_sell' => $price_sell, 'price_buy' => $price_buy, 'stuck' => $stuck, 'status' => $status];
            }
        }

        return $this->render('index', [
            'grid' => $grid,
            'shop_name' => $shop_tor->name.' - '.$shop->name,
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

    public function actionIshop()
    {
        $url = 'http://ishop-gc.ru/';
        $id_shop = 54;
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

            $price_sell = $line->item_price_out->p_out;
            $stuck = $line->item_price_out->q_out;

            if($line->item_price_in->q_in == $stuck)
                $price_buy = $line->item_price_in->p_in;
            else if($stuck != 0)
                $price_buy = round(($line->item_price_in->p_in/$line->item_price_in->q_in)*$stuck);
            else
            {
                $price_buy = $line->item_price_in->p_in;
                $stuck = $line->item_price_in->q_in;
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

            $price_sell = $line->item_price_out->p_out;
            $stuck = $line->item_price_out->q_out;

            if($line->item_price_in->q_in == $stuck)
                $price_buy = $line->item_price_in->p_in;
            else if($stuck != 0)
                $price_buy = round(($line->item_price_in->p_in/$line->item_price_in->q_in)*$stuck);
            else
            {
                $price_buy = $line->item_price_in->p_in;
                $stuck = $line->item_price_in->q_in;
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
