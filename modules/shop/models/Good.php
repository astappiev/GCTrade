<?php

namespace app\modules\shop\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Good
 * @package app\modules\shop\models
 * Model Good.
 *
 * @property integer $id
 * @property integer $shop_id
 * @property integer $item_id
 * @property integer $price_sell
 * @property integer $price_buy
 * @property integer $stuck
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Shop $shop
 * @property Item $item
 */
class Good extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_good}}';
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
            [['shop_id', 'item_id', 'stuck'], 'required'],
            [['shop_id', 'item_id', 'price_sell', 'price_buy', 'stuck'], 'integer'],
            ['price_sell', 'required', 'when' => function ($model) {
                return $model->price_buy == null;
            }, 'whenClient' => "function (attribute, value) {
                return $('#good-price_buy').val() == '';
            }"],
            ['price_buy', 'required', 'when' => function ($model) {
                return $model->price_sell == null;
            }, 'whenClient' => "function (attribute, value) {
                return $('#good-price_sell').val() == '';
            }"],
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
            'price_buy' => 'Цена покупки',
            'price_sell' => 'Цена продажи',
            'stuck' => 'Кол-во за операцию',
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

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            if ($this->price_sell == 0) {
                $this->price_sell = null;
            }

            if ($this->price_buy == 0) {
                $this->price_buy = null;
            }

            if(!$this->price_buy && !$this->price_sell) return false;

            $shop = Shop::findOne($this->shop_id);
            if($shop->user_id === \Yii::$app->user->id)
            {
                $shop->scenario = 'update_date';
                if (!$shop->save()) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}