<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%movimientos}}`.
 */
class m230912_233307_create_movimientos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%movimientos}}', [
            'id' => $this->primaryKey(),
            'id_empresa' => $this->integer(),
            'folio' => $this->string(30),
            'tipo' => $this->integer(),
            'id_consultorio' => $this->integer(),
            'id_consultorio2' => $this->integer(),
            'create_date' => $this->date(),
            'create_user' => $this->integer(),
            'update_date' => $this->date(),
            'update_user' => $this->integer(),
            'delete_date' => $this->date(),
            'delete_user' => $this->integer(),
            'observaciones' => $this->text(),
            'movimiento_relacionados' => $this->integer(),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%movimientos}}');
    }
}
