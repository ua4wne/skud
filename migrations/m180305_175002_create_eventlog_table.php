<?php

use yii\db\Migration;

/**
 * Handles the creation of table `eventlog`.
 */
class m180305_175002_create_eventlog_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('eventlog', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'user_ip' => $this->string(15)->notNull(),
            'type' => $this->string(10)->notNull(),
            'msg' => $this->string(255)->notNull(),
            'is_read' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('eventlog');
    }
}
