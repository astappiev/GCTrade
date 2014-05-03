<?php
namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * Price model
 * @property integer $id
 * @property integer $id_shop
 * @property integer $id_item
 * @property integer $price_sell
 * @property integer $price_buy
 * @property integer $stuck
 * @property integer $complaint_buy
 * @property integer $complaint_sel
 */
class Price extends ActiveRecord
{
    public static function tableName()
    {
        return 'tg_price';
    }

    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'id_item']);
    }

    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'id_shop']);
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            $shop = Shop::findOne($this->id_shop);
            if($shop->owner === Yii::$app->user->id)
            {
                $this->complaint_buy = 0;
                $this->complaint_sell = 0;

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

    public static function addPrice($id_item, $id_shop, $price_sell, $price_buy, $stuck)
    {
        $price_sell = (is_numeric($price_sell))?$price_sell:null;
        $price_buy = (is_numeric($price_buy))?$price_buy:null;

        $price = static::find()->where(['id_item' => $id_item, 'id_shop' => $id_shop])->one();
        if(!$price)
        {
            $price = new Price();
            $price->id_shop = $id_shop;
            $price->id_item = $id_item;
            $price->price_sell = $price_sell;
            $price->price_buy = $price_buy;
            $price->stuck = $stuck;
            if($price->save())
                return '<span class="glyphicon glyphicon-plus green"></span>';
            else
                return '<span class="glyphicon glyphicon-remove red"></span>';
        } else {
            if($price->price_sell == $price_sell && $price->price_buy == $price_buy && $price->stuck == $stuck) return '';

            $price->price_sell = $price_sell;
            $price->price_buy = $price_buy;
            $price->stuck = $stuck;
            if($price->save())
                return '<span class="glyphicon glyphicon-refresh blue"></span>';
            else
                return '<span class="glyphicon glyphicon-remove red"></span>';
        }
    }
}