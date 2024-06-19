<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%absensi_log}}`.
 */
class m240423_182127_create_absensi_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%absensi_log}}', [
            'id' => $this->primaryKey(),
            'id_absensi_type' =>  $this->integer()->notNull(),
            'id_absensi_status' =>  $this->integer()->notNull(),
            'tanggal_absensi' => $this->date()->notNull(),
            'day' => $this->string(255)->notNull(),
            'waktu_absensi' => $this->time()->notNull(),
            'latitude' => $this->string(255)->notNull(),
            'longitude' => $this->string(255)->notNull(),
            // 'bukti_hadir' => $this->string(255)->notNull(),
            'created_at' => $this->string(255)->notNull(),
            'updated_at' => $this->string(255)->notNull(),
            'created_by' =>  $this->integer()->notNull()           
        ]);

        $this->addForeignKey('FK_absensi_log_created_by', '{{%absensi_log}}', 'created_by', '{{%user}}', 'id');
        $this->addForeignKey('FK_absensi_log_id_absensi_type', '{{%absensi_log}}', 'id_absensi_type', '{{%absensi_type}}', 'id');
        $this->addForeignKey('FK_absensi_log_id_absensi_status', '{{%absensi_log}}', 'id_absensi_status', '{{%absensi_status}}', 'id');

        // Menambahkan trigger untuk menentukan keterangan otomatis

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('DROP TRIGGER IF EXISTS trg_set_keterangan');
        $this->dropForeignKey('FK_absensi_log_created_by', '{{%absensi_log}}');
        $this->dropForeignKey('FK_absensi_log_id_absensi_type', '{{%absensi_log}}');
        $this->dropForeignKey('FK_absensi_log_id_absensi_status', '{{%absensi_log}}');
        $this->dropTable('{{%absensi_log}}');
    }
}
