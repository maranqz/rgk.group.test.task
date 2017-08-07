<?php

use yii\db\Migration;

class m170805_091606_notification extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%notification}}',[
            'id' => $this->primaryKey(),
            'model' => $this->string()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'used' => $this->boolean()->notNull()->defaultValue(0),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%notification}}');
    }
}
