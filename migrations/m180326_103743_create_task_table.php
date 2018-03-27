<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task`.
 */
class m180326_103743_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'type' => $this->string(10)->notNull(),
            'snum' => $this->string(10)->notNull(),
            'json' => $this->text()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('task');
    }
}
