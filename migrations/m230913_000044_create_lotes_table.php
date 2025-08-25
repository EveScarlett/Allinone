<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%lotes}}`.
 */
class m230913_000044_create_lotes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%lotes}}', [
            'id' => $this->primaryKey(),
            'id_movimiento' => $this->integer(),
            'id_empresa' => $this->integer(),
            'folio' => $this->string(30),
            'id_consultorio' => $this->integer(),
            'id_insumo' => $this->integer(),
            'fecha_caducidad' => $this->date(),
            'fecha_registro' => $this->date(),
            'cantidad' => $this->integer(),
            'create_date' => $this->date(),
            'create_user' => $this->integer(),
            'update_date' => $this->date(),
            'update_user' => $this->integer(),
            'delete_date' => $this->date(),
            'delete_user' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%lotes}}');
    }
}
