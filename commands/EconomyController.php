<?php
namespace app\commands;
use yii\console\Controller;
use yii\db\Query;

class EconomyController extends Controller
{

    // The command "yii economy/index"
    public function actionIndex()
    {
        $file = file_get_contents("https://greencubes.org/api.php?type=economy");
        $data = json_decode($file, true);

        if($data["status"] == 0)
        {
            if(in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' )))
            {
                $time = date('Y-m-d H:i:s', $data["time"]);
            }
            else
            {
                $time = date('Y-m-d H:i:s', $data["time"]+2*60*60);
            }
            $dailymoney = $data["economy"]["dailymoney"];

            $connection = \Yii::$app->db;
            $command = $connection->createCommand('SELECT * FROM tg_other_economy ORDER BY time DESC');
            $economy = $command->queryOne();

            if($time != $economy["time"])
            {
                $connection->createCommand()->insert('tg_other_economy', [
                    'time' => $time,
                    'value' => $dailymoney,
                ])->execute();
            }
            else
            {
                echo 'Already exists.'.PHP_EOL;
            }

            echo 'Current info:'.PHP_EOL.'Date: '.$time.'   Money: '.$dailymoney.PHP_EOL;
            echo 'Last in DB:'.PHP_EOL.'Date: '.$economy["time"].'   Money: '.$economy["value"];

        }
        else
        {
            echo "Error status ):";
        }
    }
}