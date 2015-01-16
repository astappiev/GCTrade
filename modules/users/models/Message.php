<?php

namespace app\modules\users\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

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
            'id' => 'ID',
            'status' => 'Статус',
            'sender' => 'Отправитель',
            'recipient' => 'Получатель',
            'title' => 'Тема',
            'text' => 'Текст сообщения',
            'created_at' => 'Создано',
            'updated_at' => 'Последнее обновление',
        ];
    }

    /**
     * @return array
     */
    public static function getStatusArray()
    {
        return [
            self::STATUS_OBTAINED => 'Получено',
            self::STATUS_OBTAINED_NOTIFIED => 'Получено и уведомлено',
            self::STATUS_READS => 'Прочитано',
            self::STATUS_REMOVED => 'Удалено',
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
