<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%programa_trabajador}}`.
 */
class m230807_221002_create_programa_trabajador_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%programa_trabajador}}', [
            'id' => $this->primaryKey(),
            'id_trabajador' => $this->integer(),
            'id_programa' => $this->integer(),
            'fecha_inicio' => $this->date(),
            'fecha_fin' => $this->date(),
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
        $this->dropTable('{{%programa_trabajador}}');
    }
}
