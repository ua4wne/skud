<?php

use yii\db\Migration;

/**
 * Handles the creation of table `device`.
 */
class m180302_123041_create_device_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('device', [
            'id' => $this->primaryKey(),
            'type' => $this->string(10)->notNull(),
            'snum' => $this->string(10)->notNull()->unique(),
            'fware' => $this->string(10)->notNull(),
            'conn_fw' => $this->string(10)->notNull(),
            'image' => $this->string(50),
            'text' => $this->string(255),
            'is_active' => $this->boolean()->notNull()->defaultValue(0),
            'mode' => $this->smallInteger()->notNull()->defaultValue(0),
            'zone_id' => $this->integer()->notNull(),
            'address' => $this->string(15),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('device');
    }
}
