<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%poe_estudio}}`.
 */
class m230827_225507_create_poe_estudio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%poe_estudio}}', [
            'id' => $this->primaryKey(),
            'id_poe' => $this->integer(),
            'id_estudio' => $this->integer(),
            'id_tipo' => $this->integer(),
            'fecha' => $this->date(),
            'hora' => $this->time(),
            'evidencia' => $this->string(500),
            'condicion' => $this->integer(),
            'comentario' => $this->text(),
            'evolucion' => $this->integer(),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%poe_estudio}}');
    }
}
