<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trabajador_maquina".
 *
 * @property int $id
 * @property int|null $id_trabajador
 * @property int|null $id_maquina
 * @property int|null $status_trabajo
 * @property string|null $fecha_inicio
 * @property string|null $fecha_fin
 * @property int|null $status
 * @property string|null $create_date
 * @property int|null $create_user
 */
class Trabajadormaquina extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trabajador_maquina';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_trabajador', 'id_maquina', 'status_trabajo', 'fecha_inicio', 'fecha_fin', 'status', 'create_date', 'create_user'], 'default', 'value' => null],
            [['id_trabajador', 'id_maquina', 'status_trabajo', 'status', 'create_user'], 'integer'],
            [['fecha_inicio', 'fecha_fin', 'create_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_trabajador' => 'Id Trabajador',
            'id_maquina' => 'Id Maquina',
            'status_trabajo' => 'Status Trabajo',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'status' => 'Status',
            'create_date' => 'Create Date',
            'create_user' => 'Create User',
        ];
    }

    /**
     * Gets query for [[Trabajadores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajador()
    {
        return $this->hasOne(Trabajadores::class, ['id' => 'id_trabajador']);
    }

}
