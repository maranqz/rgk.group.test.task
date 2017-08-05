<?php
namespace app\commands;

use app\models\user\User;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{

    const USER_USER = 'user';
    const MANAGER_USER = 'manager';
    const ADMIN_USER = 'maranqz';

    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // add the rule
        $rule = new \app\rbac\Rules\IsAuthorRule();
        $auth->add($rule);

        /*// add permission "readPreview"
        $readPreview = $auth->createPermission('readPreview');
        $readPreview->description = 'Read preview a post';
        $auth->add($readPreview);

        // add permission "createPost"
        $readPost = $auth->createPermission('readPost');
        $readPost->description = 'Read a post';
        $auth->add($readPost);*/

        // add permission "viewPosts"
        $viewPosts = $auth->createPermission('viewPosts');
        $viewPosts->description = 'View a posts';
        $auth->add($viewPosts);

        // add permission "viewPost"
        $viewPost = $auth->createPermission('viewPost');
        $viewPost->description = 'View a post';
        $auth->add($viewPost);

        // add permission "createPost"
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        // add permission "updateOwnPost"
        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);

        // add permission "deleteOwnPost"
        $deleteOwnPost = $auth->createPermission('deleteOwnPost');
        $deleteOwnPost->description = 'delete own post';
        $deleteOwnPost->ruleName = $rule->name;
        $auth->add($deleteOwnPost);

        // add permission "updatePost"
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);
        $auth->addChild($updateOwnPost, $updatePost);

        // add permission "deletePost"
        $deletePost = $auth->createPermission('deletePost');
        $deletePost->description = 'delete post';
        $auth->add($deletePost);
        $auth->addChild($deleteOwnPost, $deletePost);


        // add role "guest"
        $guest = $auth->createRole('guest');
        $auth->add($guest);
        $auth->addChild($guest, $viewPosts);

        // add role "user"
        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $viewPost);
        $auth->addChild($user, $guest);

        // add role "manager"
        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $auth->addChild($manager, $createPost);
        $auth->addChild($manager, $updateOwnPost);
        $auth->addChild($manager, $deleteOwnPost);
        $auth->addChild($manager, $user);

        // add role "admin"
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $deletePost);
        $auth->addChild($admin, $manager);

        // Assign roles to users
        $auth->assign($user, User::findOne(['username' => self::USER_USER])->id);
        $auth->assign($manager, User::findOne(['username' => self::MANAGER_USER])->id);
        $auth->assign($admin, User::findOne(['username' => self::ADMIN_USER])->id);
    }
}