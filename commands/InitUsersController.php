<?php

namespace app\commands;

use yii\console\Controller;
use \app\commands\RbacController;
use \app\models\user\User;

/**
 * Class InitUsersController, generate three default user
 * @package app\commands
 */
class InitUsersController extends Controller
{
    protected static $usersName = [
        RbacController::USER_USER,
        RbacController::MANAGER_USER,
        RbacController::ADMIN_USER
    ];

    public function actionUp()
    {
        /** @var User[] $users */
        $users = User::find()->select('username')->where(['in', 'username', self::$usersName])->all();

        foreach (self::$usersName as $userName) {

            $continue = false;
            foreach ($users as $index => $user) {
                if ($user->username == $userName) {
                    array_splice($users, $index, 1);
                    $continue = true;
                    break;
                }
            }
            if ($continue) {
                continue;
            }

            $user = new User();

            $user->setAttributes([
                'username' => $userName,
                'email' => $userName . '@' . $userName . '.com',
                'password' => '123456',
            ]);

            $user->create();

            $user->confirm();
        }
    }

    public function actionDown()
    {
        $users = User::find()->where(['in', 'username', self::$usersName])->all();

        foreach ($users as $user) {
            $user->delete();
        }
    }
}
