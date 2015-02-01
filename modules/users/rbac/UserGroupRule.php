<?php

namespace app\modules\users\rbac;


use Yii;
use yii\rbac\Rule;
use app\modules\users\models\User;

/**
 * Checks if user group matches
 */
class UserGroupRule extends Rule
{
    public $name = 'userGroup';

    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $group = Yii::$app->user->identity->role;
            if ($item->name === 'admin') {
                return $group == User::ROLE_ADMIN;
            } elseif ($item->name === 'author') {
                return $group == User::ROLE_ADMIN || $group == User::ROLE_USER;
            }
        }
        return false;
    }
}