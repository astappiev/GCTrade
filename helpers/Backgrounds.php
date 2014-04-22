<?php
namespace app\helpers;

use Yii;

class Backgrounds
{
    public static function Rand()
    {
        $img = Yii::$app->cache->get('background');
        if ($img === false){
            $folder = 'images/background';
            $images = array();
            $all_files = scandir($folder);
            foreach($all_files as $file) {
                if (!strstr($file, ".png") && !strstr($file, ".jpg")) continue;
                array_push($images, $file);
            }

            $img_random = $images[rand(0,sizeof($images)-1)];
            $img = "/".$folder."/".$img_random;

            Yii::$app->cache->set('background', $img, 600);
        }
        //else Yii::$app->cache->set('background', false, 900);
        return $img;
    }
}