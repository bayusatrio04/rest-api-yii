<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%absensi_overtime}}`.
 */
class m240525_124455_create_absensi_overtime_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%absensi_overtime}}', [
            'id' => $this->primaryKey(),
            'employee_name' => $this->string()->notNull(),
            'employee_id' => $this->string()->notNull(),
            'position' => $this->string()->notNull(),
            'overtime_date' => $this->date()->notNull(),
            'start_time' => $this->time()->notNull(),
            'end_time' => $this->time()->notNull(),
            'total_hours' => $this->decimal(5, 2)->notNull(),
            'overtime_reason' => $this->text()->notNull(),
            'employee_signature' => $this->boolean()->notNull()->defaultValue(false),
            'supervisor_signature' => $this->boolean()->notNull()->defaultValue(false),
            'manager_signature' => $this->boolean()->notNull()->defaultValue(false),
            'hrd_signature' => $this->boolean()->notNull()->defaultValue(false),
            'approval_date' => $this->date(),
            'overtime_tasks' => $this->text(),
            'overtime_rate' => $this->decimal(10, 2)->notNull(),
            'total_compensation' => $this->decimal(10, 2)->notNull(),
            'additional_notes' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%absensi_overtime}}');
    }
}
