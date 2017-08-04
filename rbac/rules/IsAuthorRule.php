<?php

namespace app\rbac\Rules;


use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Rule for manager's role
 *
 * Checks the userID to match the parameter
 */
class IsAuthorRule extends Rule
{

    public $name = 'isAuthor';

    /**
     * @param string|int $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param Item $item the auth item that needs to execute its rule
     * @param array $params parameters passed to [[CheckAccessInterface::checkAccess()]] and will be passed to the rule
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['model']) ? $params['model']->created_by == $user : false;
    }
}