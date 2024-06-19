<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employees_allowance}}`.
 */
class m240524_111221_create_employees_allowance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employees_allowance}}', [
            'id' => $this->primaryKey(),
            'tunjangan_makan' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'tunjangan_jabatan' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'tunjangan_keluarga' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'tunjangan_transport' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'tunjangan_kehadiran' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'bpjs_kesehatan' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'bpjs_ketenagakerjaan' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employees_allowance}}');
    }
}
