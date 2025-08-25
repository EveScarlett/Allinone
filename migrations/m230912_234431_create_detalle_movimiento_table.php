<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%detalle_movimiento}}`.
 */
class m230912_234431_create_detalle_movimiento_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%detalle_movimiento}}', [
            'id' => $this->primaryKey(),
            'id_movimiento' => $this->integer(),
            'id_insumo' => $this->integer(),
            'id_lote' => $this->integer(),
            'cantidad' => $this->integer(),
            'cantidad_unidad' => $this->integer(),
            'fecha' => $this->date(),
            'observaciones' => $this->string(500),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%detalle_movimiento}}');
    }
}
