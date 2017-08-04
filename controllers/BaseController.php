<?php

namespace app\controllers;

use yii\base\Event;
use yii\base\ExitException;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use yii\web\Controller;

/**
 * BaseController with using RBAC and events.
 */
class BaseController extends Controller
{

    /**
     * Event is triggered before creating new user.
     */
    const EVENT_BEFORE_CREATE = 'beforeCreate';

    /**
     * Event is triggered after creating new user.
     */
    const EVENT_AFTER_CREATE = 'afterCreate';

    /**
     * Event is triggered before updating existing user.
     */
    const EVENT_BEFORE_UPDATE = 'beforeUpdate';

    /**
     * Event is triggered after updating existing user.
     */
    const EVENT_AFTER_UPDATE = 'afterUpdate';

    /**
     * Event is triggered before deleting existing user.
     */
    const EVENT_BEFORE_DELETE = 'beforeDelete';

    /**
     * Event is triggered after deleting existing user
     */
    const EVENT_AFTER_DELETE = 'afterDelete';

    /**
     * Event is triggered before blocking existing user.
     */
    const EVENT_BEFORE_DEACTIVATE = 'beforeDeactivate';

    /**
     * Event is triggered after blocking existing user.
     */
    const EVENT_AFTER_DEACTIVATE = 'afterDeactivate';

    /**
     * Event is triggered before unblocking existing user.
     */
    const EVENT_BEFORE_ACTIVATE = 'beforeActivate';

    /**
     * Event is triggered after unblocking existing user.
     */
    const EVENT_AFTER_ACTIVATE = 'afterActivate';

    /**
     * Performs AJAX validation.
     *
     * @param array|Model $model
     *
     * @throws ExitException
     */
    protected function performAjaxValidation($model)
    {
        if (\Yii::$app->request->isAjax && !\Yii::$app->request->isPjax) {
            if ($model->load(\Yii::$app->request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                echo json_encode(ActiveForm::validate($model));
                \Yii::$app->end();
            }
        }
    }

    /**
     * Create event object
     * @param $data
     * @return object
     */
    protected function getEvent($data)
    {
        return \Yii::createObject(['class' => Event::className(), 'data' => $data]);
    }

    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param User|false $user the current user or boolean `false` in case of detached User component
     * @throws ForbiddenHttpException if the user is already logged in or in case of detached User component.
     */
    protected function denyAccess()
    {
        $user = \Yii::$app->user;
        if ($user !== false && $user->getIsGuest()) {
            $user->loginRequired();
        } else {
            throw new ForbiddenHttpException(\Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }
}