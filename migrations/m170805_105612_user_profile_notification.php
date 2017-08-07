<?php

use yii\db\Migration;

class m170805_105612_user_profile_notification extends Migration
{

    public function safeUp()
    {
        $this->addColumn('{{%profile}}', 'notification_email', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn('{{%profile}}', 'notification_browser', $this->boolean()->notNull()->defaultValue(0));

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('{{%profile}}', 'notification_email');
        $this->dropColumn('{{%profile}}', 'notification_browser');
    }
}
