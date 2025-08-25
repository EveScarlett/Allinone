<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%turnos}}`.
 */
class m230816_053235_create_turnos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%turnos}}', [
            'id' => $this->primaryKey(),
            'id_empresa' => $this->integer(),
            'turno' => $this->string(500),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%turnos}}');
    }
}
