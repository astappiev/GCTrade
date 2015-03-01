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
            'username' => Yii::t('users', 'LOGIN_FORM_USERNAME'),
            'password' => Yii::t('users', 'LOGIN_FORM_PASSWORD'),
            'rememberMe' => Yii::t('users', 'LOGIN_FORM_REMEMBER_ME'),
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
				$this->addError($attribute, Yii::t('users', 'LOGIN_FORM_VALIDATE_USER_OR_PASSWORD_ERROR'));
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
        if(empty(User::findOne(["username" => $this->username])->password_hash)) {
            Yii::$app->session->setFlash('error', Yii::t('users', 'LOGIN_FORM_VALIDATE_PASSWORD_NOT_SET') . ' ' . Html::a(Yii::t('users', 'LOGIN_FORM_VALIDATE_RESTORE_PASSWORD'), ['default/request-password-reset']) . '.');
        } else if ($this->validate()) {
			return \Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
		}

        return false;
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
