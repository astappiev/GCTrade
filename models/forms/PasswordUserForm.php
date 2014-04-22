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

    public function rules()
    {
        return [
            ['password', 'string', 'min' => 6],
            ['protect_password', 'compare', 'compareAttribute' => 'password'],
            ['old_password', 'validateOldPassword'],
            ['old_password', 'required'],
        ];
    }

    public function validateOldPassword()
    {
        $user = User::findIdentity(Yii::$app->user->identity->id);
        if (!$user->validatePassword($this->old_password)) {
            $this->addError('old_password', 'Неверен старый пароль пользователя.');
        }
    }

    public function editPassword()
    {
        $user = User::findIdentity(Yii::$app->user->id);
        if($this->password != '') $user->password = $this->password;

        if($this->password != '')
            return $user->save();
        else
            return false;
    }
}
