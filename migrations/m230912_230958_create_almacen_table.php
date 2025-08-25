<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%almacen}}`.
 */
class m230912_230958_create_almacen_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%almacen}}', [
            'id' => $this->primaryKey(),
            'id_empresa' => $this->integer(),
            'id_consultorio' => $this->integer(),
            'id_insumo' => $this->integer(),
            'stock' => $this->integer(),
            'stock_unidad' => $this->integer(),
            'update_date' => $this->date(),
            'update_user' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%almacen}}');
    }
}
