<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%documentacion}}`.
 */
class m230807_213743_create_documentacion_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%documentacion}}', [
            'id' => $this->primaryKey(),
            'documento' => $this->string(300),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%documentacion}}');
    }
}
