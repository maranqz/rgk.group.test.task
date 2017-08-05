<?php

use yii\db\Migration;

class m170805_105612_user_profile_notification extends Migration
{

    public function up()
    {
        $this->addColumn('{{%profile}}', 'notification_email', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn('{{%profile}}', 'notification_browser', $this->boolean()->notNull()->defaultValue(0));

        return true;
    }

    public function down()
    {
        $this->dropColumn('{{%profile}}', 'notification_email');
        $this->dropColumn('{{%profile}}', 'notification_browser');

        return true;
    }
}
