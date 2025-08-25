<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%puesto_riesgo}}`.
 */
class m230810_215936_create_puesto_riesgo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%puesto_riesgo}}', [
            'id' => $this->primaryKey(),
            'id_puesto' => $this->integer(),
            'id_riesgo' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%puesto_riesgo}}');
    }
}
