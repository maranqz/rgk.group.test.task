<?php

namespace app\models;

use app\models\user\User;
use izumi\longpoll\Event;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap\Html;

/**
 * This is the model class for table "{{%notification}}".
 *
 * @property integer $id
 * @property string $model
 * @property integer $item_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $used
 */
class Notification extends \yii\db\ActiveRecord
{
    /** @var string|array Default: `Yii::$app->params['adminEmail']` OR `no-reply@example.com` */
    public $sender;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification}}';
    }

    /**
     * Set created_at and created_by according time and owner id
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => time(),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'model'], 'required'],
            [['item_id', 'created_at', 'created_by', 'used'], 'integer'],
            [['model'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
            'item_id' => 'Item ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'used' => 'Used',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            Event::triggerByKey('checkNew');

            $users = User::find()->select('{{%user}}.email')->where(['blocked_at' => null])
                ->innerJoin('{{%profile}}', '{{%user}}.id = {{%profile}}.user_id')
                ->where(['{{%profile}}.notification_email' => 1])->all();

            foreach ($users as $user) {
                $this->sendMessage(
                    $user->email,
                    'New ' . $this->model,
                    'notification',
                    [
                        'model' => $this->model,
                        'item_id' => $this->item_id,
                    ]
                );
            }

            Notification::updateAll(['used' => 1], ['AND', ['used' => 0], ['!=', 'id', $this->id]]);

            $this->save();
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array $params
     *
     * @return bool
     */
    protected function sendMessage($to, $subject, $view, $params = [])
    {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = \Yii::$app->mailer;
        $mailer->getView()->theme = \Yii::$app->view->theme;

        if ($this->sender === null) {
            $this->sender = isset(\Yii::$app->params['adminEmail']) ?
                \Yii::$app->params['adminEmail']
                : 'no-reply@example.com';
        }

        return $mailer->compose(['html' => $view, 'text' => 'text/' . $view], $params)
            ->setTo($to)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->send();
    }
}
