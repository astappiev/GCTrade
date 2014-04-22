<?php
namespace app\models;

use yii\db\ActiveRecord;

class Economy extends ActiveRecord
{
    public static function tableName()
    {
        return 'tg_other_economy';
    }
}