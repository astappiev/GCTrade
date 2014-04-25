<?php
namespace app\models\forms;

use app\models\User;
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
            'password' => 'Пароль',
            'protect_password' => 'Повтор пароля',
            'old_password' => 'Старый пароль',
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
            $this->addError('old_password', 'Неверен старый пароль пользователя.');
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
