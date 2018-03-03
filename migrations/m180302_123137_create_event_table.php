<?php

use yii\db\Migration;

/**
 * Handles the creation of table `event`.
 */
class m180302_123137_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('event', [
            'id' => $this->primaryKey(),
            'device_id' => $this->integer()->notNull(),
            'event_type' => $this->string(2)->notNull(),
            'card_code' => $this->string(20),
            'flag' => $this->string(3),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('event');
    }
}
