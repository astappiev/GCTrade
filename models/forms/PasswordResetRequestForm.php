<?php
namespace app\models\forms;

use app\models\User;
use yii\base\Model;

class PasswordResetRequestForm extends Model
{
	public $email;

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
        ];
    }

	public function rules()
	{
		return [
			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'exist',
				'targetClass' => '\app\models\User',
				'filter' => ['status' => User::STATUS_ACTIVE],
				'message' => 'Пользователя с таким email не существует.'
			],
		];
	}

	public function sendEmail()
	{
		$user = User::findOne([
			'status' => User::STATUS_ACTIVE,
			'email' => $this->email,
		]);

		if ($user) {
			$user->generatePasswordResetToken();
			if ($user->save()) {
				return \Yii::$app->mail->compose('passwordResetToken', ['user' => $user])
					->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' роборот'])
					->setTo($this->email)
					->setSubject('Восстановление пароля, для ' . \Yii::$app->name)
					->send();
			}
		}

		return false;
	}
}
