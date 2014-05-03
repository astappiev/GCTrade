<?php
namespace app\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\HttpException;
use app\models\forms\LoginForm;
use app\models\User;
use app\models\forms\PasswordResetRequestForm;
use app\models\forms\ResetPasswordForm;
use app\models\forms\SignupForm;
use app\models\forms\PasswordUserForm;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new HttpException(403, 'У вас нет доступа к данной странице.');
                }
            ],
        ];
    }

    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }

    public function successCallback($client)
    {
        $attributes = $client->getUserAttributes();
        $user = User::findByUsername($attributes["username"]);

        if($user) {
            if($user->status == User::STATUS_LOCAL)
            {
                $user->status = User::STATUS_ACTIVE;
                $correct_email = ($user->email === $attributes["email"]);
                if(!$correct_email){
                    $user->email = $attributes["email"];
                    $user->password_hash = '';
                }

                if($user->save()){
                    if(!$correct_email)
                        Yii::$app->session->setFlash('warning', 'Ваш email был отличен от email в вашем профиле, он был обновлен. Так же был аннулирован ваш пароль, вы можете установить новый.');
                } else {
                    Yii::$app->session->setFlash('error', 'Произошла ошибка, возможно ваш email уже используется на другом аккаунте.');
                    return $this->goHome();
                }
            }

            $user_login = new LoginForm();
            $_POST["LoginForm"]["username"] = $user->username;
            $_POST["LoginForm"]["rememberMe"] = 1;
            $user_login->load($_POST);
            if(Yii::$app->user->login($user_login->getUser(), Yii::$app->getModule("user")->loginDuration)) {
                Yii::$app->session->setFlash('success', 'Вы успешно авторизовались через OAuth, спасибо.');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка авторизации.');
            }
        } else {
            $findByEmail = User::findOne(["email" => $attributes["email"]]);
            if($findByEmail)
            {
                $old_login = $findByEmail->username;
                $findByEmail->username = $attributes["username"];
                $findByEmail->status = User::STATUS_ACTIVE;

                if($findByEmail->save()){
                    Yii::$app->session->setFlash('warning', 'Ваш логин был отличен от логина в вашем профиле, он был изменен ('.$old_login.' => '.$findByEmail->username.').');
                } else {
                    Yii::$app->session->setFlash('error', 'Произошла ошибка, возможно ваш логин уже используется на другом аккаунте.');
                    return $this->goHome();
                }

                $user_login = new LoginForm();
                $_POST["LoginForm"]["username"] = $findByEmail->username;
                $_POST["LoginForm"]["rememberMe"] = 1;
                $user_login->load($_POST);
                if(Yii::$app->user->login($user_login->getUser(), Yii::$app->getModule("user")->loginDuration)) {
                    Yii::$app->session->setFlash('success', 'Вы успешно авторизовались через OAuth, спасибо.');
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка авторизации.');
                }
            } else {
                $user = new User;
                $user->email = $attributes["email"];
                $user->username = $attributes["username"];
                $user->status = User::STATUS_ACTIVE;
                $user->generateAuthKey();
                if ($user->save()) {
                    if (Yii::$app->getUser()->login($user)) {
                        Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировались, '.$user->username.'. По желанию, можете установить пароль.');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка регистрации.');
                }
            }
        }
        return true;
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionEdit()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = User::findIdentity(\Yii::$app->user->id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Пользователь изменен.');
            } else {
                Yii::$app->session->setFlash('error', 'Возникла ошибка при изменении пользователя.');
            }
            return $this->refresh();
        } else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

	public function actionPassword()
	{
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new PasswordUserForm();
        $user = User::findIdentity(\Yii::$app->user->id);

        if($user->password_hash == '') $model->setScenario("add");
        else $model->setScenario('edit');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->editPassword()) {
                Yii::$app->session->setFlash('success', 'Пароль изменен.');
            } else {
                Yii::$app->session->setFlash('error', 'Возникла ошибка при изменении пароля.');
            }
            return $this->refresh();
        } else {
            return $this->render('password', [
                'model' => $model,
            ]);
        }
	}

	public function actionLogin()
	{
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		} else {
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();
		return $this->goHome();
	}

	public function actionSignup()
	{
		$model = new SignupForm();
		if ($model->load(Yii::$app->request->post())) {
			$user = $model->signup();
			if ($user) {
				if (Yii::$app->getUser()->login($user)) {
					return $this->goHome();
				}
			}
		}

		return $this->render('signup', [
			'model' => $model,
		]);
	}

	public function actionRequestPasswordReset()
	{
		$model = new PasswordResetRequestForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail()) {
				Yii::$app->getSession()->setFlash('success', 'Проверьте свою электронную почту для получения дальнейших инструкций по восстановлению пароля.');
				return $this->goHome();
			} else {
				Yii::$app->getSession()->setFlash('error', 'Прошу прощение, но я не могу сбросить пароль для этого email.');
			}
		}

		return $this->render('requestPasswordResetToken', [
			'model' => $model,
		]);
	}

	public function actionResetPassword($token)
	{
		try {
			$model = new ResetPasswordForm($token);
		} catch (InvalidParamException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			Yii::$app->getSession()->setFlash('success', 'Благодарю, новый пароль сохранен.');
			return $this->goHome();
		}

		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}
}
