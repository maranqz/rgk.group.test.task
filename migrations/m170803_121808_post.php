<?php

use yii\db\Migration;

class m170803_121808_post extends Migration
{
    public function up()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'preview' => $this->string(255)->notNull(),
            'description' => $this->text()->notNull(),
            'img' => $this->string(),
            'active' => $this->boolean()->notNull()->defaultValue(1),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%post}}');
    }
}
