<?php
namespace app\modules\users\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * Class User
 * @package app\modules\users\models
 * User model
 *
 * @property integer $id
 * @property integer $role
 * @property string $email
 * @property string $new_email
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $access_token
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property \app\modules\users\models\Setting $setting
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_USER = 3;
	const ROLE_AUTHOR = 5;
	const ROLE_MODER = 8;
	const ROLE_ADMIN = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'timestamp' => TimestampBehavior::className(),
        ];
    }

    /**
     * @return array
     */
    public static function getRoleArray()
    {
        return [
            self::ROLE_USER => Yii::t('users', 'USER_ROLE_USER'),
            self::ROLE_AUTHOR => Yii::t('users', 'USER_ROLE_AUTHOR'),
            self::ROLE_MODER => Yii::t('users', 'USER_ROLE_MODER'),
            self::ROLE_ADMIN => Yii::t('users', 'USER_ROLE_ADMIN'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => array_keys(self::getRoleArray())],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('users', 'USER_ID'),
            'role' => Yii::t('users', 'USER_ROLE'),
            'email' => Yii::t('users', 'USER_EMAIL'),
            'new_email' => Yii::t('users', 'USER_NEW_EMAIL'),
            'username' => Yii::t('users', 'USER_USERNAME'),
            'password_hash' => Yii::t('users', 'USER_PASSWORD_HASH'),
            'password_reset_token' => Yii::t('users', 'USER_PASSWORD_RESET_TOKEN'),
            'access_token' => Yii::t('users', 'USER_ACCESS_TOKEN'),
            'auth_key' => Yii::t('users', 'USER_AUTH_KEY'),
            'created_at' => Yii::t('users', 'USER_CREATED_AT'),
            'updated_at' => Yii::t('users', 'USER_UPDATED_AT'),
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return User|null
     */
    public static function findByUsername($username)
    {
        return static::findOne([
            'username' => $username,
        ]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

	/**
	 * Creates a new user
	 *
	 * @param array $attributes the attributes given by field => value
	 * @return static|null the newly created model, or null on failure
	 */
	public static function create($attributes)
	{
		/** @var User $user */
		$user = new static();
		$user->setAttributes($attributes);
		$user->setPassword($attributes['password']);
		$user->generateAuthKey();
		if ($user->save()) {
			return $user;
		} else {
			return null;
		}
    }

    /**
     * @inheritdoc
     */
	public function getId()
	{
		return $this->getPrimaryKey();
	}

    /**
     * @inheritdoc
     */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

    /**
     * @inheritdoc
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @inheritdoc
     */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return Yii::$app->security->validatePassword($password, $this->password_hash);
	}

	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password_hash = Yii::$app->security->generatePasswordHash($password);
	}

    /**
     * Generates "remember me" authentication key
     */
	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->security->generateRandomString();
	}

    /**
     * Generates new password reset token
     */
	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

    /**
     * Removes password reset token
     */
	public function removePasswordResetToken()
	{
		$this->password_reset_token = null;
	}

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!$this->auth_key) {
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetting()
    {
        return $this->hasOne(Setting::className(), ['user_id' => 'id']);
    }
}