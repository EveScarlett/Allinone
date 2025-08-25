<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%insumo_stockmin}}`.
 */
class m230922_215634_create_insumo_stockmin_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%insumo_stockmin}}', [
            'id' => $this->primaryKey(),
            'id_insumo' => $this->integer(),
            'id_consultorio' => $this->integer(),
            'stock' => $this->integer(),
            'stock_unidad' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%insumo_stockmin}}');
    }
}
