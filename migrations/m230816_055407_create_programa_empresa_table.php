<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%programa_empresa}}`.
 */
class m230816_055407_create_programa_empresa_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%programa_empresa}}', [
            'id' => $this->primaryKey(),
            'id_empresa' => $this->integer(),
            'id_programa' => $this->integer(),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%programa_empresa}}');
    }
}
