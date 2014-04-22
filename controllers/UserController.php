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
                'only' => ['login', 'logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['login', 'signup'],
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
        $user = User::findOne(["email" => $attributes["email"]]);

        if($user) {
            $user_login = new LoginForm();
            $_POST["LoginForm"]["username"] = $user->username;
            $_POST["LoginForm"]["rememberMe"] = 1;
            $user_login->load($_POST);
            if(Yii::$app->user->login($user_login->getUser(), Yii::$app->getModule("user")->loginDuration)) {
                Yii::$app->session->setFlash('success', 'Вы успешно авторизовались, '.$user->username);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка авторизации.');
            }
        } else {
            $user = new User;
            $user->email = $attributes["email"];
            $user->username = current(explode('@', $attributes["email"]));
            $user->generateAuthKey();
            if ($user->save()) {
                if (Yii::$app->getUser()->login($user)) {
                    Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировали, '.$user->username);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка регистрации.');
            }
        }
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
