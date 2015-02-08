<?php

namespace app\modules\shop\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * Class Book
 * @package  app\modules\shop\models
 * Model Book.
 *
 * @property string $id
 * @property string $shop_id
 * @property string $item_id
 * @property string $name
 * @property string $author
 * @property string $description
 * @property string $price_sell
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Item $item
 * @property Shop $shop
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_book}}';
    }

    /**
     * @return array
     */
    public static function getItemArray()
    {
        return [
            3008 => 'Простая книга (защищена)',
            3000 => 'Простая книга',
            3009 => 'Золотая книга (защищена)',
            3005 => 'Золотая книга',
            3010 => 'Алмазная книга (защищена)',
            3006 => 'Алмазная книга',
            3007 => 'Обсидиановая книга',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'item_id', 'name', 'author'], 'required'],
            [['shop_id', 'item_id', 'price_sell'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['author'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Магазин',
            'item_id' => 'Предмет',
            'name' => 'Название',
            'author' => 'Автор',
            'description' => 'Описание',
            'price_sell' => 'Цена продажи',
            'created_at' => 'Создано',
            'updated_at' => 'Последнее обновление',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }
}
