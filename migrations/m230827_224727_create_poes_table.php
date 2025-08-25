<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%poes}}`.
 */
class m230827_224727_create_poes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%poes}}', [
            'id' => $this->primaryKey(),
            'id_empresa' => $this->integer(),
            'id_trabajador' => $this->integer(),
            'nombre' => $this->string(100),
            'apellidos' => $this->string(100),
            'sexo' => $this->integer(),
            'fecha_nacimiento' => $this->date(),
            'anio' => $this->integer(),
            'num_imss' => $this->string(10),
            'id_puesto' => $this->integer(),
            'id_ubicacion' => $this->integer(),
            'observaciones' => $this->text(),
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
        $this->dropTable('{{%poes}}');
    }
}
