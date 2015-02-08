<?php
namespace app\modules\shop\controllers;

use Yii;
use yii\web\Controller;
use app\modules\shop\models\Item;

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
            $status = '';

            $item = Item::find()->where(['id_primary' => $line->id, 'id_meta' => $line->data])->one();
            if(!$item)
            {
                $item = new Item();
                $item->id_primary = $line->id;
                $item->id_meta = $line->data;
                $item->alias = $line->id . ($line->data !== 0 ? ('.'.$line->data) : null);
                $item->name = $line->name;
                if($item->save())
                    $status = '<span class="glyphicon glyphicon-plus twosize green"></span>';
                else
                    $status = '<span class="glyphicon glyphicon-remove twosize red"></span>';
            } else {
                $item->name = $line->name;
                $item->save();
            }

            $icon = $_SERVER{'DOCUMENT_ROOT'}.'/web/images/items/'.$id.'.png';
            if (!file_exists($icon))
            {
                if(file_put_contents($icon, file_get_contents($line->image_url)))
                    $status .= '<span class="glyphicon glyphicon-floppy-save twosize green"></span>';
                else
                    $status .= '<span class="glyphicon glyphicon-floppy-save twosize red"></span>';
            }

            $grid[] = ['id' => $id, 'name' => $line->name, 'status' => $status];
        endforeach;

        return $this->render('item', [
            'grid' => $grid,
        ]);
    }
}
