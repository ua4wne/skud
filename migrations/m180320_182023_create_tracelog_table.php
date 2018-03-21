<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tracelog`.
 */
class m180320_182023_create_tracelog_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('tracelog', [
            'id' => $this->primaryKey(),
            'type' => $this->string(10)->notNull(),
            'msg' => $this->string(255)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tracelog');
    }
}
