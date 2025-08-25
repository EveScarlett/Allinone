<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mantenimiento_componentes".
 *
 * @property int $id
 * @property int|null $id_mantenimiento
 * @property string|null $componente
 * @property string|null $numero_serie
 * @property string|null $descripcion
 * @property string|null $foto
 * @property int|null $status
 * @property string|null $create_date
 * @property int|null $create_user
 */
class Mantenimientocomponentes extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mantenimiento_componentes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_mantenimiento', 'componente', 'numero_serie', 'descripcion', 'foto', 'status', 'create_date', 'create_user'], 'default', 'value' => null],
            [['id_mantenimiento', 'status', 'create_user'], 'integer'],
            [['create_date'], 'safe'],
            [['componente'], 'string', 'max' => 1000],
            [['numero_serie'], 'string', 'max' => 100],
            [['descripcion'], 'string', 'max' => 500],
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
            'componente' => 'Componente',
            'numero_serie' => 'Numero Serie',
            'descripcion' => 'Descripcion',
            'foto' => 'Foto',
            'status' => 'Status',
            'create_date' => 'Create Date',
            'create_user' => 'Create User',
        ];
    }

}
