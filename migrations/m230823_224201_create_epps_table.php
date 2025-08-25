<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%epps}}`.
 */
class m230823_224201_create_epps_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%epps}}', [
            'id' => $this->primaryKey(),
            'epp' => $this->string(500),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%epps}}');
    }
}
