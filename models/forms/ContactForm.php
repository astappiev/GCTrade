<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;

class ContactForm extends Model
{
	public $subject;
	public $body;
	public $verifyCode;

    public function attributeLabels()
    {
        return [
            'subject' => 'Тема сообщения',
            'body' => 'Суть проблемы',
            'verifyCode' => 'Код подтверждения'
        ];
    }

	public function rules()
	{
		return [
            [['subject', 'body'], 'required'],
			// verifyCode needs to be entered correctly
			['verifyCode', 'captcha'],
		];
	}

    /**
    * Sends an email to the specified email address using the information collected by this model.
    *
    * @param string $email the target email address
    * @return boolean whether the email was sent
    */
	public function sendEmail($toemail)
	{
        $name = Yii::$app->user->identity->username;
        $email = Yii::$app->user->identity->email;
		return Yii::$app->mail->compose()
			->setTo($toemail)
			->setFrom([$email => $name])
			->setSubject($this->subject)
			->setTextBody($this->body)
			->send();
	}
}
