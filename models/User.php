<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * User model
 *
 * @property integer $id
 * @property integer $role
 * @property integer $status
 * @property string $email
 * @property string $new_email
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $access_token
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $registration_ip
 * @property string $ban_time
 * @property string $ban_reason
 * @property integer $delivery
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DEFAULT = 8;
	const STATUS_DELETED = 0;
	const STATUS_LOCAL = 2;
	const STATUS_ACTIVE = 10;

	const ROLE_USER = 10;
	const ROLE_ADMIN = 4;
	const ROLE_GUEST = 0;

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
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'value' => function() { return date("Y-m-d H:i:s"); },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_LOCAL],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_DEFAULT, self::STATUS_LOCAL]],

            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER]],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['delivery', 'boolean'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique'],
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
        return static::findOne([
            'id' => $id,
            'status' => [self::STATUS_ACTIVE, self::STATUS_LOCAL]
        ]);
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
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne([
            'username' => $username,
            'status' => [self::STATUS_ACTIVE, self::STATUS_LOCAL]
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
            'status' => [self::STATUS_ACTIVE, self::STATUS_LOCAL],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'delivery' => 'Получать уведомления от сайта',
            'email' => 'Email',
        ];
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
		$this->auth_key = Yii::$app->security->generateRandomKey();
	}

    /**
     * Generates new password reset token
     */
	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Yii::$app->security->generateRandomKey() . '_' . time();
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
                $this->auth_key = self::generateAuthKey();
            }

            if (!$this->created_at || $this->created_at == 0) {
                $this->created_at = date("Y-m-d H:i:s");
            }

            if (!$this->registration_ip) {
                $this->registration_ip = \Yii::$app->request->userIP;
            }
            return true;
        }
        return false;
    }
}