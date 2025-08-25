<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%alergia_trabajador}}`.
 */
class m230807_215312_create_alergia_trabajador_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%alergia_trabajador}}', [
            'id' => $this->primaryKey(),
            'id_trabajador' => $this->integer(),
            'alergia' => $this->string(300),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%alergia_trabajador}}');
    }
}
