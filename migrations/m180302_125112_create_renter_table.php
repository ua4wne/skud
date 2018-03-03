<?php

use yii\db\Migration;

/**
 * Handles the creation of table `renter`.
 */
class m180302_125112_create_renter_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('renter', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'area' => $this->string(50)->notNull(),
            'agent' => $this->string(50)->notNull(),
            'phone1' => $this->string(20),
            'phone2' => $this->string(20),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('renter');
    }
}
