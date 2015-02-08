<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "tg_online".
 *
 * @property integer $time
 * @property integer $value
 *
 **/
class Economy extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->db_analytics;
    }
}