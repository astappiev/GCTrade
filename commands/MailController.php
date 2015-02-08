<?php
namespace app\commands;

use yii\console\Controller;

class MailController extends Controller
{
    // The command "yii mail/flag"
    public function actionFlag()
    {
        $connection = \Yii::$app->db;
        $shops = $connection->createCommand('SELECT tg_shop.user_id, tg_shop.name, COUNT(*) as count FROM tg_shop LEFT JOIN tg_price ON tg_shop.id = tg_price.shop_id WHERE complaint_buy > 0 OR complaint_sell > 0 GROUP BY tg_shop.id, tg_shop.name');
        $shops = $shops->queryAll();
        foreach($shops as $shop)
        {
            $email = $connection->createCommand('SELECT email, mail_delivery FROM tg_user WHERE id = '.$shop["user_id"]);
            $email = $email->queryOne();
            if($email["mail_delivery"])
            {
                \Yii::$app->mailer->compose('flagsNotify', ['name' => $shop["name"], 'count' => $shop["count"]])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' роборот'])
                    ->setTo($email["email"])
                    ->setSubject('Уведомление о статусе товаров, для '.$shop["name"])
                    ->send();
                echo 'Send to '.$shop["name"].' -> '.$email["email"].PHP_EOL;
            }
            else
            {
                echo 'No send '.$shop["name"].' (without mail_delivery) -> '.$email["email"].PHP_EOL;
            }

        }
    }

    // The command "yii mail/see"
    public function actionSee()
    {
        $connection = \Yii::$app->db;
        $result = $connection->createCommand('SELECT tg_user.id AS user_id, tg_user.email, tg_user.username, tg_see.id AS see_id, tg_see.login, tg_see.description FROM tg_user INNER JOIN tg_user_setting ON tg_user.id = tg_user_setting.user_id INNER JOIN tg_see ON tg_user.id = tg_see.user_id WHERE tg_user_setting.mail_see_leave = 1 AND tg_see.is_send = 0');
        $result = $result->queryAll();

        if(count($result) > 0) {
            foreach($result as $row)
            {
                $request = "https://api.greencubes.org/users/".$row["login"];

                $curl_handle =  curl_init();
                curl_setopt($curl_handle, CURLOPT_URL, $request);
                curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
                curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl_handle, CURLOPT_USERAGENT, 'GCTrade');
                $exec = curl_exec($curl_handle);
                curl_close($curl_handle);

                $login_data = json_decode($exec);

                if($login_data->lastseen->main < (time() - (21 * 24 * 60 * 60))) {
                    $connection->createCommand()->update('tg_see', ['is_send' => 1], ['id' => $row["see_id"]])->execute();

                    \Yii::$app->mailer->compose('seeNotify', ['username' => $row["username"], 'see_login' => $row["login"], 'see_description' => $row["description"], 'see_visited' => $login_data->lastseen->main])
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' роборот'])
                        ->setTo($row["email"])
                        ->setSubject('Хорошие новости, один из игроков забыл о необходимости посещать игру :)')
                        ->send();

                    echo 'Send '.$row["username"].' -> '.$row["login"].' is not playing'.PHP_EOL;
                } else {
                    $result = $connection->createCommand('SELECT tg_see.is_send FROM tg_see WHERE id = '.$row["see_id"]);
                    $result = $result->queryOne();
                    if($result["is_send"] == 1) {
                        $connection->createCommand()->update('tg_user_see', ['is_send' => 0], ['id' => $row["see_id"]])->execute();
                    }
                    echo 'No send '.$row["username"].' -> '.$row["login"].' is playing'.PHP_EOL;
                }

            }
        } else {
            echo 'Nothing send'.PHP_EOL;
        }

        return 0;
    }
}