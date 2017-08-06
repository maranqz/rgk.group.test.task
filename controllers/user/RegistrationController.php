<?php

namespace app\controllers\user;

use dektrium\user\Finder;

class RegistrationController extends \dektrium\user\controllers\RegistrationController
{

    /**
     * RegistrationController constructor.
     * Adds a role when user registering
     *
     * @param string $id
     * @param \yii\base\Module $module
     * @param Finder $finder
     * @param array $config
     */
    public function __construct($id, \yii\base\Module $module, Finder $finder, array $config = [])
    {
        parent::__construct($id, $module, $finder, $config);

        $callback = [\app\controllers\user\RegistrationController::className(), 'setDefaultRole'];
        if ($this->module->enableConfirmation) {//If need confirm registration by mail
            $this->on(self::EVENT_AFTER_CONFIRM, $callback);
        } else {
            $this->on(self::EVENT_AFTER_REGISTER, $callback);
        }
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