<?php

namespace app\modules\users\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tg_message".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $id_sender
 * @property integer $id_recipient
 * @property string $title
 * @property string $text
 * @property integer $created_at
 * @property integer $updated_at
 */
class Message extends ActiveRecord
{
    const STATUS_OBTAINED = 1;
    const STATUS_OBTAINED_NOTIFIED  = 2;
    const STATUS_READS = 3;
    const STATUS_REMOVED = 4;

    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            ['id_sender', 'default', 'value' => Yii::$app->user->id],
            ['status', 'default', 'value' => self::STATUS_OBTAINED],

            [['title', 'text'], 'string'],
            [['title'], 'string', 'max' => 255],

            ['status', 'in', 'range' => array_keys(self::getStatusArray())],

            [['id_recipient', 'title', 'text'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
            'sender' => 'Отправитель',
            'id_sender' => 'Отправитель',
            'recipient' => 'Получатель',
            'id_recipient' => 'Получатель',
            'title' => 'Тема',
            'text' => 'Текст сообщения',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @return array Массив статусов
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
     * @return string Статус сообщения
     */
    public function getStatus()
    {
        $type = self::getStatusArray();
        return $type[$this->status];
    }

    public static function getCount()
    {
        return self::find()->where(['id_recipient' => Yii::$app->user->id, 'status' => [self::STATUS_OBTAINED_NOTIFIED, self::STATUS_OBTAINED]])->count();
    }

    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'id_sender']);
    }

    public function getRecipient()
    {
        return $this->hasOne(User::className(), ['id' => 'id_recipient']);
    }
}
