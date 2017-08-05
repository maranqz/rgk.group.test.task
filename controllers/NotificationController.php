<?php

namespace app\controllers;

use app\models\Notification;
use izumi\longpoll\Server;
use yii\web\Controller;

class NotificationController extends Controller
{
    public function actions()
    {
        return [
            'polling' => [
                'class' => 'izumi\longpoll\LongPollAction',
                'events' => ['checkNew'],
                'callback' => [$this, 'checkNewCallback'],
            ],
        ];
    }

    public function checkNewCallback(Server $server)
    {
        $result = [];
        $notifications = Notification::find()
            ->select(['model', 'item_id'])
            ->where(['used' => 0])->andWhere(['!=', 'created_by', \Yii::$app->user->id])
            ->orderBy('id DESC')
            ->all();

        foreach ($notifications as $notification) {
            $result[] = $notification->getAttributes();
        }
        $server->responseData = $result;
    }
}