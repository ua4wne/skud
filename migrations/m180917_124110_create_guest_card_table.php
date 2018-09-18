<?php

use yii\db\Migration;

/**
 * Handles the creation of table `guest_card`.
 */
class m180917_124110_create_guest_card_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('guest_card', [
            'id' => $this->primaryKey(),
            'visitor_id' => $this->integer()->notNull(),
            'card' => $this->string(20)->notNull(),
            'issued' => $this->dateTime()->notNull(),
            'passed' => $this->dateTime(),
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('guest_card');
    }
}
