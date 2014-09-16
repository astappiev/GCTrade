<?php
namespace app\modules\users\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use app\modules\users\models\User;


/**
 * Login form
 */
class LoginForm extends Model
{
	public $username;
	public $password;
	public $rememberMe = true;

	private $_user = false;

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня (Ваш компьютер)',
        ];
    }

	public function rules()
	{
		return [
			// username and password are both required
			[['username', 'password'], 'required'],
			// rememberMe must be a boolean value
			['rememberMe', 'boolean'],
			// password is validated by validatePassword()
			['password', 'validatePassword'],
		];
	}

	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 */
	public function validatePassword($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$user = $this->getUser();
			if (!$user || !$user->validatePassword($this->password)) {
				$this->addError($attribute, 'Неверное имя пользователя или пароль.');
			}
		}
	}

	/**
	 * Logs in a user using the provided username and password.
	 *
	 * @return boolean whether the user is logged in successfully
	 */
	public function login()
	{
        if(User::findOne(["username" => $this->username])->password_hash == null)
            Yii::$app->session->setFlash('error', 'Вероятно ваша учетная запись перенесена из старого сайта, вам необходимо воспользоваться функцией '.Html::a('«восстановить пароль»', ['user/request-password-reset']).'.');;

        if ($this->validate()) {
			return \Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
		} else {
			return false;
		}
	}

	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	public function getUser()
	{
		if ($this->_user === false) {
			$this->_user = User::findByUsername($this->username);
		}
		return $this->_user;
	}
}
