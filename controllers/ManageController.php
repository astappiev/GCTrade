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
        $source = json_decode(file_get_contents('https://api.greencubes.org/main/items'));

        $grid = [];
        foreach($source as $line):
            $id = ($line->data === 0)?$line->id:$line->id.'.'.$line->data;
            $name = $line->name;
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

            $icon = $_SERVER{'DOCUMENT_ROOT'}.'/images/items/'.$id.'.png';
            if (!file_exists($icon))
            {
                if(file_put_contents($icon, file_get_contents($line->image_url)))
                    $status .= '<span class="glyphicon glyphicon-floppy-save green"></span>';
                else
                    $status .= '<span class="glyphicon glyphicon-floppy-save red"></span>';
            }

            $grid[] = ['id' => $id, 'name' => $name, 'status' => $status];
        endforeach;

        return $this->render('item', [
            'grid' => $grid,
        ]);
    }
}
