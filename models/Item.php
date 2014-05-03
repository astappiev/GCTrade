<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Item
 * @package app\models
 * Модель товара.
 *
 * @property integer $id
 * @property string $alias
 * @property string $name
 */
class Item extends ActiveRecord
{
    public static function tableName()
    {
        return 'tg_item';
    }

    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['id_item' => 'id']);
    }

    public static function findByAlias($alias)
    {
        return static::findOne(['alias' => $alias]);
    }

    public static function findByName($name)
    {
        $item = static::findOne(['name' => $name]);
        if(!$item) return ItemUsernames::findByName($name)->item;
        else return $item;
    }
}