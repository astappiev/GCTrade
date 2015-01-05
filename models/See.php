<?php

namespace app\models;

use app\modules\users\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class See
 * @package app\models
 * Model See.
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $login
 * @property string $description
 * @property integer $is_send
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \app\modules\users\models\User $user
 */
class See extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%see}}';
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
            [['login', 'description'], 'string'],
            [['login'], 'string', 'max' => 255],

            ['user_id', 'default', 'value' => \Yii::$app->user->id],

            [['user_id', 'login'], 'required'],
            [['user_id', 'is_send'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'Пользователь',
            'login' => 'логин игрока',
            'description' => 'Описание',
            'is_send' => 'Отправлено?',
            'created_at' => 'Создано',
            'updated_at' => 'Последнее обновление',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}