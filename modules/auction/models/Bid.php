<?php

namespace app\modules\auction\models;

use Yii;
use app\modules\users\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Bid
 * @package app\modules\auction\models
 * Model Bid.
 *
 * @property integer $id
 * @property integer $lot_id
 * @property integer $user_id
 * @property integer $cost
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Lot $lot
 * @property \app\modules\users\models\User $user
 */
class Bid extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auction_bid}}';
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
            ['user_id', 'default', 'value' => Yii::$app->user->id],

            [['lot_id', 'user_id', 'cost'], 'required'],
            [['lot_id', 'user_id', 'cost'], 'integer'],
            ['cost', 'integer', 'min' => 1],
            ['cost', 'integer', 'max' => 1000000000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('auction', 'BID_ID'),
            'lot' => Yii::t('auction', 'BID_LOT'),
            'user' => Yii::t('auction', 'BID_USER'),
            'cost' => Yii::t('auction', 'BID_COST'),
            'created_at' => Yii::t('auction', 'BID_CREATED_AT'),
            'updated_at' => Yii::t('auction', 'BID_UPDATED_AT'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->orderBy('cost', SORT_DESC);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLot()
    {
        return $this->hasOne(Lot::className(), ['id' => 'lot_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
