<?php

namespace app\modules\users\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Url;
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

        if ($user) {
            if ($user->role == User::ROLE_USER) {
                $user->role = User::ROLE_AUTHOR;
                $isEmailMatches = $user->email === $attributes["email"];
                if (!$isEmailMatches) {
                    $user->email = $attributes["email"];
                    $user->password_hash = null;
                }
                $user->access_token = $client->accessToken->getToken()["token"];

                if ($user->save()) {
                    Yii::$app->session->setFlash('info', Yii::t('users', 'DEFAULT_CONTROLLER_AUTH_EXIST_USERNAME'));
                    if(!$isEmailMatches) {
                        Yii::$app->session->setFlash('warning', Yii::t('users', 'DEFAULT_CONTROLLER_AUTH_EXIST_USERNAME_PASSWORD_RESET', ['url' => Url::to(['default/index'])]));
                    }
                }
            }
        } else {
            $user = User::findOne(["email" => $attributes["email"]]);
            if ($user) {
                $oldLogin = $user->username;
                $user->username = $attributes["username"];
                $user->role = User::ROLE_AUTHOR;
                $user->access_token = $client->accessToken->getToken()["token"];

                if($user->save()) {
                    Yii::$app->session->setFlash('info', Yii::t('users', 'DEFAULT_CONTROLLER_AUTH_EXIST_EMAIL', ['login' => $oldLogin]));
                }
            } else {
                $user = new User;
                $user->role = User::ROLE_AUTHOR;
                $user->email = $attributes["email"];
                $user->username = $attributes["username"];
                $user->access_token = $client->accessToken->getToken()["token"];
                if ($user->save()) {
                    Yii::$app->session->setFlash('info', Yii::t('users', 'DEFAULT_CONTROLLER_AUTH_REGISTER_SUCCESS'));
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('users', 'DEFAULT_CONTROLLER_AUTH_REGISTER_FAILED'));
                }
            }
        }

        if (Yii::$app->user->login($user, 2592000)) {
            Yii::$app->session->setFlash('success', Yii::t('users', 'DEFAULT_CONTROLLER_AUTH_SUCCESS'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('users', 'DEFAULT_CONTROLLER_AUTH_FAILED'));
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
