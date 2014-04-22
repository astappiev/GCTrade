<?php
namespace app\models;

use yii\db\ActiveRecord;

class ItemUsernames extends ActiveRecord
{
    public static function tableName()
    {
        return 'tg_item_usernames';
    }

    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'id']);
    }

    public static function findByName($name)
    {
        return static::findOne(['name' => $name]);
    }
}