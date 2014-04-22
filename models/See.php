<?php
namespace app\models;

use yii\db\ActiveRecord;

class See extends ActiveRecord
{
    public static function tableName()
    {
        return 'tg_other_see';
    }
}