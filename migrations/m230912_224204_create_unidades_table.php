<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%unidades}}`.
 */
class m230912_224204_create_unidades_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%unidades}}', [
            'id' => $this->primaryKey(),
            'unidades' => $this->string(400),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%unidades}}');
    }
}
