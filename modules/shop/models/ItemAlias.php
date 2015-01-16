<?php

namespace app\modules\shop\models;

use yii\db\ActiveRecord;

/**
 * Class ItemItemAlias
 * @package app\modules\shop\models
 * Model ItemItemAlias.
 *
 * @property integer $item_id
 * @property string $subname
 *
 * @property \app\modules\shop\models\Item $item
 */
class ItemAlias extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%item_alias}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * Finds the ItemAlias model based on its name value.
     * @param string $name
     * @return ItemAlias model
     */
    public static function findByName($name)
    {
        return static::findOne(['name' => $name]);
    }
}