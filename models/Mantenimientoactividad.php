<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mantenimiento_actividad".
 *
 * @property int $id
 * @property int|null $id_mantenimiento
 * @property string|null $actividad
 * @property string|null $foto
 * @property int|null $status
 * @property string|null $create_date
 * @property int|null $create_user
 */
class Mantenimientoactividad extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mantenimiento_actividad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_mantenimiento', 'actividad', 'foto', 'status', 'create_date', 'create_user'], 'default', 'value' => null],
            [['id_mantenimiento', 'status', 'create_user'], 'integer'],
            [['create_date'], 'safe'],
            [['actividad'], 'string', 'max' => 1000],
            [['foto'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_mantenimiento' => 'Id Mantenimiento',
            'actividad' => 'Actividad',
            'foto' => 'Foto',
            'status' => 'Status',
            'create_date' => 'Create Date',
            'create_user' => 'Create User',
        ];
    }

}
