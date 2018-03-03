<?php

use yii\db\Migration;

/**
 * Handles the creation of table `visitor`.
 */
class m180302_123157_create_visitor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('visitor', [
            'id' => $this->primaryKey(),
            'fname' => $this->string(50)->notNull(),
            'mname' => $this->string(50)->defaultValue(null),
            'lname' => $this->string(50)->notNull(),
            'image' => $this->string(30)->defaultValue(null),
            'renter_id' => $this->integer()->notNull(),
            'status' => $this->boolean()->defaultValue(0),
            'car_id' => $this->integer(),
            'car_num' => $this->string(10),
            'doc_type' => $this->string(30),
            'doc_series' => $this->string(7),
            'doc_num' => $this->string(10),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('visitor');
    }
}
