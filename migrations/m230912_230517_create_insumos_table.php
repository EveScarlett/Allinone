<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%insumos}}`.
 */
class m230912_230517_create_insumos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%insumos}}', [
            'id' => $this->primaryKey(),
            'id_empresa' => $this->integer(),
            'tipo' => $this->integer(),
            'nombre_comercial' => $this->string(500),
            'nombre_generico' => $this->string(500),
            'foto' => $this->string(300),
            'concentracion' => $this->string(300),
            'fabricante' => $this->string(300),
            'formula' => $this->string(300),
            'condiciones_conservacion' => $this->string(300),
            'id_presentacion' => $this->integer(),
            'id_unidad' => $this->integer(),
            'cantidad' => $this->integer(),
            'create_date' => $this->date(),
            'create_user' => $this->integer(),
            'update_date' => $this->date(),
            'update_user' => $this->integer(),
            'delete_date' => $this->date(),
            'delete_user' => $this->integer(),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%insumos}}');
    }
}
