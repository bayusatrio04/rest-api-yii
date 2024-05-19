<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%absensi_type}}`.
 */
class m240423_182057_create_absensi_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%absensi_type}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(255)->notNull(),
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
        $this->dropTable('{{%absensi_type}}');
    }
}
