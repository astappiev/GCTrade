<?php
namespace app\modules\users\models\forms;

use app\modules\users\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
	public $username;
	public $email;
	public $password;

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('users', 'SIGNUP_FORM_USERNAME'),
            'email' => Yii::t('users', 'SIGNUP_FORM_EMAIL'),
            'password' => Yii::t('users', 'SIGNUP_FORM_PASSWORD'),
        ];
    }

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['username', 'filter', 'filter' => 'trim'],
			['username', 'required'],
			['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => Yii::t('users', 'SIGNUP_FORM_RULES_USERNAME_UNIQUE_MESSAGE')],

			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'unique', 'targetClass' => '\app\models\User', 'message' => Yii::t('users', 'SIGNUP_FORM_RULES_USERNAME_UNIQUE_EMAIL')],

			['password', 'required'],
			['password', 'string', 'min' => 6],
		];
	}

	/**
	 * Signs user up.
	 *
	 * @return User|null the saved model or null if saving fails
	 */
	public function signup()
	{
		if ($this->validate()) {
			return User::create($this->attributes);
		}
		return null;
	}
}
