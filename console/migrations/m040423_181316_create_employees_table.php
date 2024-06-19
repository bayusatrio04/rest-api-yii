<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employees}}`.
 */
class m040423_181316_create_employees_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employees}}', [
            'id' => $this->primaryKey(),
            'nama_depan' => $this->string(255)->Null(),
            'nama_belakang' => $this->string(255)->Null(),
            'email' => $this->string(255)->Null()->unique(),
            'tanggal_lahir' => $this->date()->Null(),
            'jenis_kelamin' => $this->string(255)->Null(),
            'status_nikah'=> $this->string(255)->Null(),
            'jumlah_tanggungan'=> $this->integer()->defaultValue(0),
            'no_telepon' => $this->string(16)->Null()->unique(),
            'position_id' => $this->integer()->Null(),
            'type_karyawan' => $this->string(255)->defaultValue("Full Time"),
            'created_at' => $this->string(255)->Null(),
            'updated_at' => $this->string(255)->Null()

            
        ]);

        $this->addForeignKey('FK_employees_position_id', '{{%employees}}', 'position_id', '{{%employees_position}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_employees_position_id', '{{%employees}}');
        $this->dropTable('{{%employees}}');
    }
}
