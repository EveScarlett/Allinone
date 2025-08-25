<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%puestos_trabajo}}`.
 */
class m230807_221420_create_puestos_trabajo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%puestos_trabajo}}', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string(300),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%puestos_trabajo}}');
    }
}
