<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%absensi_task}}`.
 */
class m240423_182137_create_absensi_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%absensi_task}}', [
            'id' => $this->primaryKey(),
            'judul_task' =>  $this->string(255)->notNull(),
            'deskripsi_task' =>  $this->string(255)->notNull(),
            'latitude' => $this->string(255)->notNull(),
            'longitude' => $this->string(255)->notNull(),
            'alamat_task' => $this->text()->notNull(),
            'created_at' => $this->string(255)->notNull(),
            'updated_at' => $this->string(255)->notNull(),
            'created_by' =>  $this->integer()->notNull()
            
        ]);
        $this->addForeignKey('FK_absensi_task_created_by', '{{%absensi_task}}', 'created_by', '{{%user}}', 'id');
       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_absensi_task_created_by', '{{%absensi_task}}');
        $this->dropTable('{{%absensi_task}}');
    }
}
