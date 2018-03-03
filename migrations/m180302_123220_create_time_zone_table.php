<?php

use yii\db\Migration;

/**
 * Handles the creation of table `time_zone`.
 */
class m180302_123220_create_time_zone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('time_zone', [
            'id' => $this->primaryKey(),
            'zone' => $this->smallInteger(3)->notNull()->unique(),
            'begin' =>$this->time()->notNull(),
            'end' => $this->time()->notNull(),
            'days' => $this->string(8)->notNull(),
            'text' => $this->string(255),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('time_zone');
    }
}
