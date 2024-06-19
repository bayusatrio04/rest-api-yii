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
            'position_salary_id' => $this->integer(),
            'position_name' => $this->string(255)->notNull(),
            'description' => $this->string(255)->notNull(),
            'created_at' => $this->string(255)->notNull(),
            'updated_at' => $this->string(255)->notNull()
        ]);
        // $this->addForeignKey('FK_employees_position_position_salary_id', '{{%employees_position}}', 'position_salary_id', '{{%employees_position_salaries}}', 'id');
        $this->addForeignKey('FK_employees_position_position_salary_id', '{{%employees_position}}', 'position_salary_id', '{{%employees_position_salaries}}', 'id');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_employees_position_position_salary_id', '{{%employees_position}}');
        $this->dropTable('{{%employees_position}}');
    }
}
