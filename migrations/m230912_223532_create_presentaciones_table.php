<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%presentaciones}}`.
 */
class m230912_223532_create_presentaciones_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%presentaciones}}', [
            'id' => $this->primaryKey(),
            'presentacion' => $this->string(400),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%presentaciones}}');
    }
}
