<?php

use yii\db\Migration;

/**
 * Handles the creation of table `idcard`.
 */
class m180302_123107_create_idcard_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('idcard', [
            'id' => $this->primaryKey(),
            'code' => $this->string(20)->notNull()->unique(),
            'granted' => $this->boolean()->notNull()->defaultValue(0),
            'flags' => $this->smallInteger(),
            'zone' => $this->smallInteger()->notNull(),
            'share' => $this->boolean()->notNull()->defaultValue(0),
            'visitor_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('idcard');
    }
}
