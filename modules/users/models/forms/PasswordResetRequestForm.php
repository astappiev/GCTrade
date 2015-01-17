<?php
namespace app\modules\users\models\forms;

use app\modules\users\models\User;
use yii\base\Model;
use Yii;

class PasswordResetRequestForm extends Model
{
	public $email;

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('users', 'PASSWORD_RESET_REQUEST_FORM_EMAIL'),
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
				'message' => Yii::t('users', 'PASSWORD_RESET_REQUEST_FORM_RULES_EMAIL_EXIST_MESSAGE')
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
				return \Yii::$app->mailer->compose('passwordResetToken', ['user' => $user])
					->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' роборот'])
					->setTo($this->email)
					->setSubject('Восстановление пароля, для ' . \Yii::$app->name)
					->send();
			}
		}

		return false;
	}
}
