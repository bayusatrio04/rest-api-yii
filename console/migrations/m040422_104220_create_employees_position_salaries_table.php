<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employees_position_salaries}}`.
 */
class m040422_104220_create_employees_position_salaries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employees_position_salaries}}', [
            'id' => $this->primaryKey(),
            'gaji_pokok' => $this->decimal(15, 2)->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employees_position_salaries}}');
    }
}
