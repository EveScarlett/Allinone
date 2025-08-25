<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%areas}}`.
 */
class m230807_214506_create_areas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%areas}}', [
            'id' => $this->primaryKey(),
            'id_empresa' => $this->integer(),
            'area' => $this->string(300),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%areas}}');
    }
}
