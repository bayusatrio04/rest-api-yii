<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employees_position}}`.
 */
class m040423_181315_create_employees_position_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employees_position}}', [
            'id' => $this->primaryKey(),
            'position' => $this->string(255)->notNull(),
            'description' => $this->string(255)->notNull(),
            'created_at' => $this->string(255)->notNull(),
            'updated_at' => $this->string(255)->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employees_position}}');
    }
}
