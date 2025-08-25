<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%trabajador_estudio}}`.
 */
class m230831_030915_create_trabajador_estudio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%trabajador_estudio}}', [
            'id' => $this->primaryKey(),
            'id_trabajador' => $this->integer(),
            'id_estudio' => $this->integer(),
            'id_periodicidad' => $this->integer(),
            'fecha_documento' => $this->date(),
            'fecha_vencimiento' => $this->date(),
            'evidencia' => $this->string(500),
            'conclusion' => $this->string(500),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%trabajador_estudio}}');
    }
}
