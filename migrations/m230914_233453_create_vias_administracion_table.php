<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vias_administracion}}`.
 */
class m230914_233453_create_vias_administracion_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vias_administracion}}', [
            'id' => $this->primaryKey(),
            'via_administracion' => $this->string(300),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%vias_administracion}}');
    }
}
