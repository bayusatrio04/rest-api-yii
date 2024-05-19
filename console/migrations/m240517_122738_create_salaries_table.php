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
            'position_salary_id' => $this->integer()->notNull(),
            'salary_date' => $this->date()->notNull(),
            'basic_salary' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'meal_allowance' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'tax_amount' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'total_salary' => $this->decimal(15, 2)->notNull()->defaultValue(0),
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
        $this->addForeignKey(
            'fk-salaries-position_salary_id',
            '{{%salaries}}',
            'position_salary_id',
            '{{%position_salaries}}',
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

        $this->dropForeignKey(
            'fk-salaries-position_salary_id',
            '{{%salaries}}'
        );


        $this->dropTable('{{%salaries}}');
    }
}
