<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%historial_documentos}}`.
 */
class m230911_010556_create_historial_documentos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%historial_documentos}}', [
            'id' => $this->primaryKey(),
            'id_empresa' => $this->integer(),
            'id_trabajador' => $this->integer(),
            'id_tipo' => $this->integer(),
            'id_estudio' => $this->integer(),
            'id_periodicidad' => $this->integer(),
            'fecha_documento' => $this->date(),
            'fecha_vencimiento' => $this->date(),
            'evidencia' => $this->string(300),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%historial_documentos}}');
    }
}
