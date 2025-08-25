<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%requerimiento_empresa}}`.
 */
class m230907_192852_create_requerimiento_empresa_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%requerimiento_empresa}}', [
            'id' => $this->primaryKey(),
            'id_empresa' => $this->integer(),
            'id_tipo' => $this->integer(),
            'id_estudio' => $this->integer(),
            'id_periodicidad' => $this->integer(),
            'id_status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%requerimiento_empresa}}');
    }
}
