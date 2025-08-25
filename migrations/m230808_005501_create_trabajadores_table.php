<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%trabajadores}}`.
 */
class m230808_005501_create_trabajadores_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%trabajadores}}', [
            'id' => $this->primaryKey(),
            'id_empresa' => $this->integer(),
            'tipo_examen' => $this->integer(),
            'nombre' => $this->string(100),
            'apellidos' => $this->string(100),
            'foto' => $this->string(100),
            'sexo' => $this->integer(),
            'estado_civil' => $this->integer(),
            'fecha_nacimiento' => $this->date(),
            'edad' => $this->integer(),
            'nivel_lectura' => $this->integer(),
            'nivel_escritura' => $this->integer(),
            'escolaridad' => $this->integer(),
            'grupo' => $this->integer(),
            'rh' => $this->integer(),
            'num_imss' => $this->string(10),
            'celular' => $this->string(30),
            'contacto_emergencia' => $this->string(100),
            'direccion' => $this->string(100),
            'colonia' => $this->string(100),
            'pais' => $this->string(100),
            'estado' => $this->string(100),
            'ciudad' => $this->string(100),
            'municipio' => $this->string(100),
            'cp' => $this->string(10),
            'num_trabajador' => $this->string(10),
            'tipo_contratacion' => $this->integer(),
            'fecha_contratacion' => $this->date(),
            'fecha_baja' => $this->date(),
            'antiguedad' => $this->string(50),
            'ruta' => $this->string(100),
            'parada' => $this->string(100),
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
        $this->dropTable('{{%trabajadores}}');
    }
}
