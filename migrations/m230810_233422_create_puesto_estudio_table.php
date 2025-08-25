<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%puesto_estudio}}`.
 */
class m230810_233422_create_puesto_estudio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%puesto_estudio}}', [
            'id' => $this->primaryKey(),
            'id_puesto' => $this->integer(),
            'id_estudio' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%puesto_estudio}}');
    }
}
