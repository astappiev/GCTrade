<?php

namespace app\modules\users\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\HttpException;
use app\modules\users\models\forms\LoginForm;
use app\modules\users\models\User;
use app\modules\users\models\forms\PasswordResetRequestForm;
use app\modules\users\models\forms\ResetPasswordForm;
use app\modules\users\models\forms\SignupForm;
use app\modules\users\models\forms\PasswordUserForm;
use app\modules\users\models\search\Message as MessageSearch;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['signup', 'auth', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['signup', 'auth', 'login'],
                        'allow' => false,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function () {
                    throw new HttpException(403, Yii::t('users', 'DEFAULT_CONTROLLER_RULES_NOT_PERMISSIONS'));
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

        $attributes["username"] = preg_replace('!\@.*!is', '', $attributes["username"]);
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
                    return $this->goBack();
                }
            }

            $user->access_token = $client->accessToken->getToken()["token"];
            $user->save();

            $user_login = new LoginForm();
            $user_login->username = $user->username;
            if(Yii::$app->user->login($user_login->getUser(), 2592000)) {
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
                    return $this->goBack();
                }

                $user_login = new LoginForm();
                $user_login->username = $findByEmail->username;
                if(Yii::$app->user->login($user_login->getUser(), 2592000)) {
                    Yii::$app->session->setFlash('success', 'Вы успешно авторизовались через OAuth, спасибо.');
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка авторизации.');
                }
            } else {
                $user = new User;
                $user->email = $attributes["email"];
                $user->username = $attributes["username"];
                $user->access_token = $client->accessToken->getToken()["token"];
                $user->status = User::STATUS_ACTIVE;
                $user->generateAuthKey();
                if ($user->save()) {
                    if (Yii::$app->getUser()->login($user)) {
                        Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировались, '.$user->username.'.');
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
        $model = User::findOne(\Yii::$app->user->id);
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionMessages()
    {
        $searchModel = new MessageSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('message-index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionEdit()
    {
        $model = User::findOne(Yii::$app->user->id);
        if ($model->load(Yii::$app->request->post()) && $model->setting->load(Yii::$app->request->post()) && $model->validate() && $model->setting->validate()) {
            if ($model->save() && $model->setting->save()) {
                Yii::$app->session->setFlash('success', Yii::t('users', 'DEFAULT_CONTROLLER_USER_UPDATED'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('users', 'DEFAULT_CONTROLLER_USER_NOT_UPDATE'));
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
        $model = new PasswordUserForm();
        $user = User::findIdentity(\Yii::$app->user->id);

        if($user->password_hash == '') $model->setScenario("add");
        else $model->setScenario('edit');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->editPassword()) {
                Yii::$app->session->setFlash('success', Yii::t('users', 'DEFAULT_CONTROLLER_SET_PASSWORD_SUCCESS'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('users', 'DEFAULT_CONTROLLER_SET_PASSWORD_ERROR'));
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
				Yii::$app->getSession()->setFlash('success', Yii::t('users', 'DEFAULT_CONTROLLER_REQUEST_PASSWORD_SUCCESS'));
				return $this->goHome();
			} else {
				Yii::$app->getSession()->setFlash('error', Yii::t('users', 'DEFAULT_CONTROLLER_REQUEST_PASSWORD_ERROR'));
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
			Yii::$app->getSession()->setFlash('success', Yii::t('users', 'DEFAULT_CONTROLLER_RESET_PASSWORD_SUCCESS'));
			return $this->goHome();
		}

		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}
}
