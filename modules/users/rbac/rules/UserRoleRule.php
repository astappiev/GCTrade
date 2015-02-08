<?php

namespace app\modules\users\rbac\rules;

use Yii;
use yii\rbac\Rule;
use app\modules\users\models\User;

/**
 * Checks if user group matches
 */
class UserRoleRule extends Rule
{
    public $name = 'userRole';

    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;
            if ($item->name === 'admin') {
                return $role == User::ROLE_ADMIN;
            } elseif ($item->name === 'moder') {
                return $role == User::ROLE_ADMIN || $role == User::ROLE_MODER;
            } elseif ($item->name === 'author') {
                return $role == User::ROLE_ADMIN || $role == User::ROLE_MODER || $role == User::ROLE_AUTHOR;
            } elseif ($item->name === 'user') {
                return $role == User::ROLE_ADMIN || $role == User::ROLE_MODER || $role == User::ROLE_AUTHOR || $role == User::ROLE_USER;
            }
        }
        return false;
    }
}