<?php

use yii\db\Migration;

/**
 * Handles the creation of table `event_type`.
 */
class m180302_123253_create_event_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('event_type', [
            'id' => $this->primaryKey(),
            'text' => $this->string(70)->notNull(),
            'code' => $this->smallInteger()->notNull()->unique(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('event_type');
    }
}
