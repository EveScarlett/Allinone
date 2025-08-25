<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%usuarios}}`.
 */
class m230917_204613_create_usuarios_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%usuarios}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(300),
            'username' => $this->string(150),
            'password' => $this->string(150),
            'rol' => $this->integer(),
            'firma' => $this->string(100),
            'authKey' => $this->string(200),
            'accessToken' => $this->string(200),
            'id_firma' => $this->integer(),
            'id_empresa' => $this->integer(),
            'foto' => $this->string(100),
            'empresas_todos' => $this->integer(),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%usuarios}}');
    }
}
