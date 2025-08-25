<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%consultorios}}`.
 */
class m230807_214922_create_consultorios_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%consultorios}}', [
            'id' => $this->primaryKey(),
            'id_empresa' => $this->integer(),
            'consultorio' => $this->string(300),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%consultorios}}');
    }
}
