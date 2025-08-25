<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%consultas}}`.
 */
class m230819_023032_create_consultas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%consultas}}', [
            'id' => $this->primaryKey(),
            'id_trabajador' => $this->integer(),
            'id_empresa' => $this->integer(),
            'id_consultorio' => $this->integer(),
            'tipo' => $this->integer(),
            'folio' => $this->string(50),
            'fecha' => $this->date(),
            'visita' => $this->integer(),
            'solicitante' => $this->integer(),
            'hora_inicio' => $this->time(),
            'hora_fin' => $this->time(),
            'sexo' => $this->integer(),
            'num_imss' => $this->string(20),
            'area' => $this->integer(),
            'puesto' => $this->integer(),
            'evidencia' => $this->string(500),
            'fc' => $this->string(10),
            'fr' => $this->string(10),
            'temp' => $this->string(10),
            'ta' => $this->string(10),
            'ta_diastolica' => $this->string(10),
            'pulso' => $this->string(10),
            'oxigeno' => $this->string(10),
            'peso' => $this->double(),
            'talla' => $this->double(),
            'imc' => $this->string(20),
            'categoria_imc' => $this->string(50),
            'sintomatologia' => $this->string(1000),
            'aparatos' => $this->string(500),
            'alergias' => $this->string(1000),
            'embarazo' => $this->string(500),
            'diagnosticocie' => $this->string(500),
            'diagnostico' => $this->string(1000),
            'estudios' => $this->string(1000),
            'manejo' => $this->string(1000),
            'seguimiento' => $this->string(1000),
            'resultado' => $this->integer(),
            'tipo_padecimiento' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%consultas}}');
    }
}
