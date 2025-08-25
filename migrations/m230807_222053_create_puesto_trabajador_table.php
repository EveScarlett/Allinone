<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%puesto_trabajador}}`.
 */
class m230807_222053_create_puesto_trabajador_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%puesto_trabajador}}', [
            'id' => $this->primaryKey(),
            'id_trabajador' => $this->integer(),
            'id_puesto' => $this->integer(),
            'area' => $this->string(300),
            'fecha_inicio' => $this->date(),
            'fecha_fin' => $this->date(),
            'teamleader' => $this->string(300),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%puesto_trabajador}}');
    }
}
