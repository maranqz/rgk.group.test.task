<?php

namespace app\controllers\user;

use dektrium\user\Finder;

class RegistrationController extends \dektrium\user\controllers\RegistrationController
{

    public function __construct($id, \yii\base\Module $module, Finder $finder, array $config = [])
    {
        if ($this->module->enableConfirmation) {
            $this->on(self::EVENT_AFTER_CONFIRM, ['\app\controllers\RegistrationController', 'setDefaultRole']);
        } else {
            $this->on(self::EVENT_AFTER_REGISTER, ['\app\controllers\RegistrationController', 'setDefaultRole']);
        }

        parent::__construct($id, $module, $finder, $config);
    }

    /**
     *
     * @param \dektrium\user\events\UserEvent $event
     *
     * @return void
     */
    static function setDefaultRole($event)
    {
        $user = $event->getUser();
        $auth = \Yii::$app->authManager;
        $userRole = $auth->getRole('user');
        $auth->assign($userRole, $user->id);
    }
}