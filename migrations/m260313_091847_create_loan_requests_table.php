<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%loan_requests}}`.
 */
class m260313_091847_create_loan_requests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%loan_requests}}', [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer()->unsigned()->notNull(),
            'amount'     => $this->decimal(12, 2)->unsigned()->notNull(),
            'term'       => $this->smallInteger()->unsigned()->notNull(),
            'status'     => $this->string(20)->defaultValue('pending')->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('NOW()')->notNull(),
        ]);
        $this->createIndex('idx-loan-user-status', '{{%loan_requests}}', ['user_id', 'status']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%loan_requests}}');
    }
}
