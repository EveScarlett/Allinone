<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%documento_trabajador}}`.
 */
class m230807_215822_create_documento_trabajador_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%documento_trabajador}}', [
            'id' => $this->primaryKey(),
            'id_trabajador' => $this->integer(),
            'id_documento' => $this->integer(),
            'fecha' => $this->date(),
            'documento' => $this->string(100),
            'upload_date' => $this->date(),
            'upload_user' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%documento_trabajador}}');
    }
}
