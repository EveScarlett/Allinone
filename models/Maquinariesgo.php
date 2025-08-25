<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "maquina_riesgo".
 *
 * @property int $id
 * @property int|null $id_maquina
 * @property string|null $riesgo
 * @property int|null $status
 * @property string|null $create_date
 * @property int|null $create_user
 * @property string|null $update_date
 * @property int|null $update_user
 */
class Maquinariesgo extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'maquina_riesgo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_maquina', 'riesgo', 'status', 'create_date', 'create_user', 'update_date', 'update_user'], 'default', 'value' => null],
            [['id_maquina', 'status', 'create_user', 'update_user'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['riesgo'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_maquina' => 'Id Maquina',
            'riesgo' => 'Riesgo',
            'status' => 'Status',
            'create_date' => 'Create Date',
            'create_user' => 'Create User',
            'update_date' => 'Update Date',
            'update_user' => 'Update User',
        ];
    }

}
