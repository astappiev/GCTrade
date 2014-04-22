<?php
namespace app\commands;

use yii\console\Controller;

class MailController extends Controller
{
    // The command "yii mail/flag"
    public function actionFlag()
    {
        $connection = \Yii::$app->db;
        $shops = $connection->createCommand('SELECT tg_shop.owner, tg_shop.name, COUNT(*) as count FROM tg_shop LEFT JOIN tg_price ON tg_shop.id = tg_price.id_shop WHERE complaint_buy > 0 OR complaint_sell > 0 GROUP BY tg_shop.id, tg_shop.name');
        $shops = $shops->queryAll();
        foreach($shops as $shop)
        {
            $email = $connection->createCommand('SELECT email, delivery FROM tg_user WHERE id = '.$shop["owner"]);
            $email = $email->queryOne();
            if($email["delivery"])
            {
                \Yii::$app->mail->compose('flagsNotify', ['name' => $shop["name"], 'count' => $shop["count"]])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' роборот'])
                    ->setTo($email["email"])
                    ->setSubject('Уведомление о статусе товаров, для '.$shop["name"])
                    ->send();
                echo 'Send to '.$shop["name"].' -> '.$email["email"].PHP_EOL;
            }
            else
            {
                echo 'No send '.$shop["name"].' (without delivery) -> '.$email["email"].PHP_EOL;
            }

        }
    }
}