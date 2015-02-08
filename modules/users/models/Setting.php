<?php

namespace app\modules\users\models;

use Yii;

/**
 * Class Setting
 * @package app\modules\users\models
 * Setting model
 *
 * @property string $user_id
 * @property integer $mail_delivery
 * @property integer $mail_see_leave
 *
 * @property User $user
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'mail_delivery', 'mail_see_leave'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'Номер пользователя',
            'mail_delivery' => 'Получать уведомления от сайта',
            'mail_see_leave' => 'Получать уведомления, когда отслеживаемый пользователь перестает играть',
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
