<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%absensi_status}}`.
 */
class m240423_182105_create_absensi_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%absensi_status}}', [
            'id' => $this->primaryKey(),
            'status' => $this->string(255)->notNull(),
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
        $this->dropTable('{{%absensi_status}}');
    }
}
