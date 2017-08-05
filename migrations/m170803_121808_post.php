<?php

use yii\db\Migration;
use \app\models\Post;
use \app\models\user\User;
use \app\commands\RbacController;

class m170803_121808_post extends Migration
{
    public function safeUp()
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

        if (YII_ENV_DEV) {
            $faker = \Faker\Factory::create('ru_RU');

            $user = User::find()->where([
                'in',
                'username',
                [
                    RbacController::USER_USER,
                    RbacController::MANAGER_USER,
                    RbacController::ADMIN_USER
                ]
            ])->all();

            try {
                for ($i = 0; $i < 50; $i++) {
                    $this->insert('{{%post}}', [
                        'title' => $faker->title,
                        'preview' => $faker->text(255),
                        'description' => $faker->text(1024),
                        'img' => '',
                        'active' => $faker->numberBetween(0, 1),
                        'created_at' => $faker->numberBetween(0, time()),
                        'created_by' => $user[mt_rand(0, 2)]->id
                    ]);
                }
            } catch (\Exception $e) {
                $this->down();
                \Yii::warning($e->getMessage());
                throw $e;
            }
        }

        return true;
    }


    public function down()
    {
        $this->dropTable('{{%post}}');
        return true;
    }
}
