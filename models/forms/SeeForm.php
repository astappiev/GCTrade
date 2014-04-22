<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\See;

class SeeForm extends Model
{
	public $login;
	public $description;

    public function attributeLabels()
    {
        return [
            'login' => 'Логин игрока',
            'description' => 'Краткое описание',
        ];
    }

	public function rules()
	{
		return [
            [['login'], 'required'],
            [['description'], 'string'],
            ['login', 'validateLogin'],
		];
	}

    public function validateLogin()
    {
        $lastseen = json_decode(file_get_contents("https://greencubes.org/api.php?type=lastseen&nick=".$this->login));
        if($lastseen->status != 0){
            $this->addError('login', 'Данный пользователь не существует.');
            Yii::$app->session->setFlash('error', 'Данный пользователь, '.$this->login.' не существует.');
        }
    }

	public function AddLogin()
	{
        $login = new See;
        $login->user_id = Yii::$app->user->identity->id;
        $login->login = $this->login;
        $login->description = $this->description;
        if ($login->save()) {
            return $login;
        } else {
            return null;
        }
	}
}