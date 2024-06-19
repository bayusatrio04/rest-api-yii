<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employees_tax_}}`.
 */
class m240524_111525_create_employees_tax_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employees_tax}}', [
            'id' => $this->primaryKey(),
            'percentage' => $this->decimal(15,3),
            'masa_berlaku' => $this->integer()->notNull()->defaultValue(date('Y')),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employees_tax_}}');
    }
}
