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
            'card' => $this->string(20)->notNull()->unique(),
            'renter_id' => $this->integer()->notNull(),
            'car_id' => $this->integer(),
            'car_num' => $this->string(10),
            'doc_id' => $this->integer()->notNull(),
            'doc_series' => $this->string(7),
            'doc_num' => $this->string(10),
            'phone' => $this->string(20),
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
