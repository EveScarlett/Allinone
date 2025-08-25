<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%riesgos}}`.
 */
class m230811_004043_create_riesgos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%riesgos}}', [
            'id' => $this->primaryKey(),
            'riesgo' => $this->string(500),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%riesgos}}');
    }
}
