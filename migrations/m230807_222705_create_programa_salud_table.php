<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%programa_salud}}`.
 */
class m230807_222705_create_programa_salud_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%programa_salud}}', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string(300),
            'descripcion' => $this->text(),
            'color' => $this->string(30),
            'vigencia' => $this->integer(),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%programa_salud}}');
    }
}
