<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Item;
use app\helpers\ParseHTML;

class ManageController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionItem()
    {
        $html = file_get_contents('http://greencubes.org/?action=list');
        $source = new ParseHTML($html);
        $tr = $source->get('table.itemtable tr')->toArray();
        unset($tr[0]);

        $grid = [];
        foreach($tr as $line):
            $id = str_replace(".0", "", str_replace(", ", ".", $line['td'][1]["#text"]));
            $name = $line['td'][2]["#text"];
            $status = '';

            $item = Item::findByAlias($id);
            if(!$item)
            {
                $item = new Item();
                $item->alias = $id;
                $item->name = $name;
                if($item->save())
                    $status = '<span class="glyphicon glyphicon-plus green"></span>';
                else
                    $status = '<span class="glyphicon glyphicon-remove red"></span>';
            }
            $grid[] = ['id' => $id, 'name' => $name, 'status' => $status];
        endforeach;

        return $this->render('item', [
            'grid' => $grid,
        ]);
    }
}
