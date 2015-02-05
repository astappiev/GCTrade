<?php

namespace app\modules\users\controllers;

use Yii;
use yii\console\Controller;
use app\modules\users\rbac\rules\OwnerRule;
use app\modules\users\rbac\rules\UserRoleRule;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Create roles
        $guest  = $auth->createRole('guest');
        $guest->description = 'Only-read';
        $user   = $auth->createRole('user');
        $user->description = 'User';
        $author = $auth->createRole('author');
        $author->description = 'Can create';
        $moder  = $auth->createRole('moder');
        $moder->description = 'Can moderate';
        $admin  = $auth->createRole('admin');
        $admin->description = 'Good';

        // Create permissions
        $createShop = $auth->createPermission('createShop');
        $createShop->description = 'Create Shops';
        $createAuction = $auth->createPermission('createAuction');
        $createAuction->description = 'Create Auctions';
        $updateShop = $auth->createPermission('updateShop');
        $updateShop->description = 'Update Shops';
        $updateAuction = $auth->createPermission('updateAuction');
        $updateAuction->description = 'Update Auctions';
        $updateUsers = $auth->createPermission('updateUsers');
        $updateUsers->description = 'Update Users';
        $updateOwnShop = $auth->createPermission('updateOwnShop');
        $updateOwnShop->description = 'Update own Shops';
        $updateOwnAuction = $auth->createPermission('updateOwnLot');
        $updateOwnAuction->description = 'Update own Shops';

        // Add permissions in Yii::$app->authManager
        $auth->add($createShop);
        $auth->add($createAuction);
        $auth->add($updateShop);
        $auth->add($updateAuction);
        $auth->add($updateUsers);
        $auth->add($updateOwnShop);
        $auth->add($updateOwnAuction);


        // Add rule, based on defaults rule
        $userRoleRule = new UserRoleRule();
        $auth->add($userRoleRule);

        // Add rule "UserRoleRule" in roles
        $guest->ruleName  = $userRoleRule->name;
        $user->ruleName  = $userRoleRule->name;
        $author->ruleName = $userRoleRule->name;
        $moder->ruleName  = $userRoleRule->name;
        $admin->ruleName  = $userRoleRule->name;

        // Add rule, based on owners
        $ownerRule = new OwnerRule();
        $auth->add($ownerRule);

        // Add rule "OwnerRule" in roles
        $updateOwnShop->ruleName = $ownerRule->name;
        $updateOwnAuction->ruleName = $ownerRule->name;

        // Add roles in Yii::$app->authManager
        $auth->add($guest);
        $auth->add($user);
        $auth->add($author);
        $auth->add($moder);
        $auth->add($admin);

        // Add permission-per-role in Yii::$app->authManager
        // Guest
        //$auth->addChild($guest, $login);

        // User
        $auth->addChild($user, $createShop);
        $auth->addChild($user, $updateOwnShop);

        // Author
        $auth->addChild($author, $createAuction);
        $auth->addChild($author, $user);
        $auth->addChild($author, $updateOwnAuction);

        // Moder
        $auth->addChild($moder, $updateShop);
        $auth->addChild($moder, $updateAuction);
        $auth->addChild($moder, $author);

        // Admin
        $auth->addChild($admin, $updateUsers);
        $auth->addChild($admin, $moder);

        // Role-per-role
        $auth->addChild($updateOwnShop , $updateShop);
        $auth->addChild($updateOwnAuction , $updateAuction);
    }
}