<?php

namespace app\modules\shop\models;

use yii\db\ActiveRecord;

/**
 * Class ItemUsernames
 * @package app\modules\shop\models
 * Model ItemUsernames.
 *
 * @property integer $id
 * @property string $name
 */
class ItemUsernames extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tg_item_usernames';
    }

    /**
     * Get Item model corresponding to the current model.
     * @return Item model
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'id']);
    }

    /**
     * Finds the ItemUsernames model based on its name value.
     * @param string $name
     * @return ItemUsernames model
     */
    public static function findByName($name)
    {
        return static::findOne(['name' => $name]);
    }
}