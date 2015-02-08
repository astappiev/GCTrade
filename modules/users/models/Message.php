<?php

namespace app\modules\users\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * Class Message
 * @package app\modules\users\models
 * Model Message.
 *
 * @property integer $id
 * @property integer $status
 * @property integer $user_sender
 * @property integer $user_recipient
 * @property string $title
 * @property string $text
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property integer $count
 * @property \app\modules\users\models\User $sender
 * @property \app\modules\users\models\User $recipient
 */
class Message extends ActiveRecord
{
    const STATUS_OBTAINED = 1;
    const STATUS_OBTAINED_NOTIFIED  = 2;
    const STATUS_READS = 3;
    const STATUS_REMOVED = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_message}}';
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
            ['user_sender', 'default', 'value' => \Yii::$app->user->id],
            ['status', 'default', 'value' => self::STATUS_OBTAINED],

            [['title', 'text'], 'string'],
            [['title'], 'string', 'max' => 255],

            ['status', 'in', 'range' => array_keys(self::getStatusArray())],

            [['user_recipient', 'title', 'text'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('users', 'MESSAGE_ID'),
            'status' => Yii::t('users', 'MESSAGE_STATUS'),
            'sender' => Yii::t('users', 'MESSAGE_SENDER'),
            'recipient' => Yii::t('users', 'MESSAGE_RECIPIENT'),
            'title' => Yii::t('users', 'MESSAGE_TITLE'),
            'text' => Yii::t('users', 'MESSAGE_TEXT'),
            'created_at' => Yii::t('users', 'MESSAGE_CREATED_AT'),
            'updated_at' => Yii::t('users', 'MESSAGE_UPDATED_AT'),
        ];
    }

    /**
     * @return array
     */
    public static function getStatusArray()
    {
        return [
            self::STATUS_OBTAINED => Yii::t('users', 'MESSAGE_STATUS_OBTAINED'),
            self::STATUS_OBTAINED_NOTIFIED => Yii::t('users', 'MESSAGE_STATUS_OBTAINED_NOTIFIED'),
            self::STATUS_READS => Yii::t('users', 'MESSAGE_STATUS_READS'),
            self::STATUS_REMOVED => Yii::t('users', 'MESSAGE_STATUS_REMOVED'),
        ];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        $type = self::getStatusArray();
        return $type[$this->status];
    }

    /**
     * @return integer
     */
    public static function getCount()
    {
        return self::find()->where(['user_recipient' => \Yii::$app->user->id, 'status' => [self::STATUS_OBTAINED_NOTIFIED, self::STATUS_OBTAINED]])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'user_sender']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(User::className(), ['id' => 'user_recipient']);
    }
}
