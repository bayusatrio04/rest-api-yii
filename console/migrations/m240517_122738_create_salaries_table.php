<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%salaries}}`.
 */
class m240517_122738_create_salaries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%salaries}}', [
            'id' => $this->primaryKey(),
            'employee_id' => $this->integer()->notNull(),
            'tanggal_penggajian' => $this->date()->notNull(),
            'total_gaji_pokok' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'total_tunjangan_jabatan' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'total_tunjangan_keluarga' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'total_tunjangan_makan' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'total_tunjangan_transport' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'total_tunjangan_kehadiran' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'total_bpjs_kesehatan' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'total_bpjs_ketenagakerjaan' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'persentase_pajak_pph_21' => $this->decimal(15, 6)->notNull()->defaultValue(0),
            'total_pajak_pph_21' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'total_gaji' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
        $this->addForeignKey(
            'fk-salaries-employee_id',
            '{{%salaries}}',
            'employee_id',
            '{{%employees}}',
            'id',
            'CASCADE'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-salaries-employee_id',
            '{{%salaries}}'
        );




        $this->dropTable('{{%salaries}}');
    }
}
