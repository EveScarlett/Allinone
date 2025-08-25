<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hccohc_estudio}}`.
 */
class m230905_012003_create_hccohc_estudio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hccohc_estudio}}', [
            'id' => $this->primaryKey(),
            'id_hccohc' => $this->integer(),
            'id_tipo' => $this->integer(),
            'id_estudio' => $this->integer(),
            'fecha' => $this->date(),
            'evidencia' => $this->string(300),
            'conclusion' => $this->integer(),
            'comentario' => $this->string(1000),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%hccohc_estudio}}');
    }
}
