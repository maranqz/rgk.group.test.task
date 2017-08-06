<?php

namespace app\models\user;

/**
 * Class User
 * @package app\models\user
 *
 * @property Profile $profile
 */
class User extends \dektrium\user\models\User
{

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if (!$insert) {
            if (!empty($this->password) && !$this->mailer->sendGeneratedPassword($this, $this->password)) {
                return false;
            }
        }

        return parent::beforeSave($insert);
    }
}
