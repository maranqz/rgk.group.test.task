<?php

namespace app\models\user;

/**
 * Class Profile extend \dektrium\user\models\Profile
 * @package app\models\user
 *
 * @property boolean $notification_email
 * @property boolean $notification_browser
 */
class Profile extends \dektrium\user\models\Profile
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            ['notification_email', 'notification_browser'], 'boolean',
        ];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['notification_email'] = 'Notification by email';
        $labels['notification_browser'] = 'Notification by browser';

        return $labels;
    }
}
