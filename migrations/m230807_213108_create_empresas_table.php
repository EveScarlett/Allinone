<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%empresas}}`.
 */
class m230807_213108_create_empresas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%empresas}}', [
            'id' => $this->primaryKey(),
            'razon' => $this->string(300),
            'comercial' => $this->string(300),
            'abreviacion' => $this->string(5),
            'rfc' => $this->string(30),
            'pais' => $this->string(100),
            'estado' => $this->string(100),
            'ciudad' => $this->string(100),
            'municipio' => $this->string(100),
            'logo' => $this->string(100),
            'contacto' => $this->string(300),
            'telefono' => $this->string(30),
            'correo' => $this->string(100),
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
        $this->dropTable('{{%empresas}}');
    }
}
