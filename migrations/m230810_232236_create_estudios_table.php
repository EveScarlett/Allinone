<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%estudios}}`.
 */
class m230810_232236_create_estudios_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%estudios}}', [
            'id' => $this->primaryKey(),
            'estudio' => $this->string(300),
            'periodicidad' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%estudios}}');
    }
}
