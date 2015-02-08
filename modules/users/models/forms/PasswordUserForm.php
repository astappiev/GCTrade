<?php
namespace app\modules\users\models\forms;

use app\modules\users\models\User;
use yii\base\Model;
use Yii;

class PasswordUserForm extends Model
{
	public $password;
    public $protect_password;
    public $old_password;

    public function attributeLabels()
    {
        return [
            'password' => Yii::t('users', 'PASSWORD_USER_FORM_PASSWORD'),
            'protect_password' => Yii::t('users', 'PASSWORD_USER_FORM_PROTECT_PASSWORD'),
            'old_password' => Yii::t('users', 'PASSWORD_USER_FORM_OLD_PASSWORD'),
        ];
    }

    public function scenarios()
    {
        return [
            'add' => ['password', 'protect_password'],
            'edit' => ['old_password', 'password', 'protect_password'],
            'default' => ['old_password', 'password', 'protect_password'],
        ];
    }

    public function rules()
    {
        return [
            [['old_password', 'password', 'protect_password'], 'string'],
            ['password', 'string', 'min' => 6],
            ['protect_password', 'compare', 'compareAttribute' => 'password'],
            ['old_password', 'validateOldPassword'],
            ['old_password', 'required', 'on' => 'edit'],
        ];
    }

    public function validateOldPassword()
    {
        $user = User::findIdentity(Yii::$app->user->id);

        if (!$user->validatePassword($this->old_password)) {
            $this->addError('old_password', Yii::t('users', 'PASSWORD_USER_FORM_INVALID_OLD_PASSWORD'));
        }
    }

    public function editPassword()
    {
        $user = User::findIdentity(Yii::$app->user->id);
        $user->setPassword($this->password);

        if($this->password != '')
            return $user->save();
        else
            return false;
    }
}
