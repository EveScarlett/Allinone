<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ubicaciones}}`.
 */
class m230807_214352_create_ubicaciones_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ubicaciones}}', [
            'id' => $this->primaryKey(),
            'id_empresa' => $this->integer(),
            'ubicacion' => $this->string(300),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ubicaciones}}');
    }
}
