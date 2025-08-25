<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%puesto_epp}}`.
 */
class m230823_225521_create_puesto_epp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%puesto_epp}}', [
            'id' => $this->primaryKey(),
            'id_puesto' => $this->integer(),
            'id_epp' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%puesto_epp}}');
    }
}
