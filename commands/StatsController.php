<?php
namespace app\commands;
use yii\console\Controller;
use yii\db\Query;

class StatsController extends Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->runAction('stats/economy'))
        {
            echo 'Economy success update'.PHP_EOL;
        }
        else
        {
            echo 'Error: update economy'.PHP_EOL;
        }

        if(\Yii::$app->runAction('stats/online'))
        {
            echo 'Online success update'.PHP_EOL;
        }
        else
        {
            echo 'Error: update online'.PHP_EOL;
        }
    }

    public function actionEconomy()
    {
        date_default_timezone_set('UTC');

        $file = file_get_contents("https://api.greencubes.org/main/economy");
        $data = json_decode($file, true);

        if($data["status"] == 0)
        {
            $time = $data["time"];
            $value = $data["economy"]["dailymoney"];

            $connection = \Yii::$app->db_analytics;
            $command = $connection->createCommand('SELECT * FROM tg_economy ORDER BY time DESC LIMIT 1');
            $economy = $command->queryOne();

            if($time != $economy["time"])
            {
                $connection->createCommand()->insert('tg_economy', [
                    'time' => $time,
                    'value' => $value,
                ])->execute();
            }
            return true;
        }
        return false;
    }

    public function actionOnline()
    {
        date_default_timezone_set('UTC');

        $file = file_get_contents("https://api.greencubes.org/main/status");
        $data = json_decode($file, true);

        if($data["status"])
        {
            $time = time();
            $value = $data["online"];

            $connection = \Yii::$app->db_analytics;
            $command = $connection->createCommand('SELECT * FROM tg_online ORDER BY time DESC LIMIT 1');
            $online = $command->queryOne();

            if($time - $online["time"] > 3600)
            {
                $connection->createCommand()->insert('tg_online', [
                    'time' => $time,
                    'value' => $value,
                ])->execute();
            }
            return true;
        }
        return false;
    }
}