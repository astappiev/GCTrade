<?php
namespace app\models;

use yii\db\ActiveRecord;

class Economy extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->db_analytics;
    }

    public static function tableName()
    {
        return 'tg_economy';
    }
}