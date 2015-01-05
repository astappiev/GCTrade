<?php

namespace app\modules\shop\models;

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
            $shop = Shop::findOne($this->shop_id);
            if($shop->user_id === \Yii::$app->user->id)
            {
                $shop->scenario = 'update_date';
                if ($shop->save()) {
                    return $shop;
                } else {
                    return null;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public static function addPrice($item_id, $shop_id, $price_sell, $price_buy, $stuck)
    {
        $price_sell = (is_numeric($price_sell))?$price_sell:null;
        $price_buy = (is_numeric($price_buy))?$price_buy:null;

        $price = self::find()->where(['item_id' => $item_id, 'shop_id' => $shop_id])->one();
        if(!$price)
        {
            $price = new self();
            $price->shop_id = $shop_id;
            $price->item_id = $item_id;
            $price->price_sell = $price_sell;
            $price->price_buy = $price_buy;
            $price->stuck = $stuck;
            if($price->save())
                return '<span class="glyphicon glyphicon-plus twosize green"></span>';
            else
                return '<span class="glyphicon glyphicon-remove twosize red"></span>';
        } else {
            if($price->price_sell == $price_sell && $price->price_buy == $price_buy && $price->stuck == $stuck) return '';

            $price->price_sell = $price_sell;
            $price->price_buy = $price_buy;
            $price->stuck = $stuck;
            if($price->save())
                return '<span class="glyphicon glyphicon-refresh twosize blue"></span>';
            else
                return '<span class="glyphicon glyphicon-remove twosize red"></span>';
        }
    }
}