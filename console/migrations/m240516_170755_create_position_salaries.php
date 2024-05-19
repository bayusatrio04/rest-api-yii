<?php

use yii\db\Migration;

/**
 * Class m240516_170755_create_position_salaries
 */
class m240516_170755_create_position_salaries extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%position_salaries}}', [
            'id' => $this->primaryKey(),
            'position_id' => $this->integer()->notNull(),
            'basic_salary' => $this->decimal(15,2)->notNull(),
            'meal_allowance' => $this->decimal(15,2)->notNull(),
            'tax_percentage' => $this->decimal(5,2)->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
        $this->addForeignKey('FK_position_salaries_id', '{{%position_salaries}}', 'position_id', '{{%employees_position}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_position_salaries_position_id', '{{%position_salaries}}');
        $this->dropTable('{{%position_salaries}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240516_170755_create_position_salaries cannot be reverted.\n";

        return false;
    }
    */
}
